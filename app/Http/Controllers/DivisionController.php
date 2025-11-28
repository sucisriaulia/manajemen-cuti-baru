<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    // 1. MENAMPILKAN DAFTAR DIVISI
    public function index()
    {
        // Mengambil data divisi beserta data manajernya (Ketua)
        $divisions = Division::with('manager')->get();
        return view('admin.divisions.index', compact('divisions'));
    }

    // 2. FORM TAMBAH DIVISI
    public function create()
    {
        // Ambil daftar user dengan role 'ketua_divisi'
        // Logika: Kita cari ketua_divisi yang belum terpakai di tabel divisions
        $usedManagerIds = Division::pluck('manager_id')->toArray();
        
        $candidates = User::where('role', 'ketua_divisi')
            ->whereNotIn('id', $usedManagerIds)
            ->get();

        return view('admin.divisions.create', compact('candidates'));
    }

    // 3. SIMPAN DIVISI BARU (DIPERBAIKI)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:divisions,name',
            'manager_id' => 'required|exists:users,id', // Wajib pilih ketua
            'description' => 'nullable|string',
        ]);

        // GENERATE CODE OTOMATIS
        // Contoh: "IT Support" -> "IT-SUPPORT"
        $generatedCode = strtoupper(str_replace(' ', '-', $request->name));

        // Simpan ke Database (Sertakan 'code')
        $division = Division::create([
            'name' => $request->name,
            'code' => $generatedCode, // <--- PENAMBAHAN PENTING
            'description' => $request->description,
            'manager_id' => $request->manager_id,
        ]);

        // Update kolom 'division' di tabel user milik si Ketua agar sinkron
        $manager = User::find($request->manager_id);
        if ($manager) {
            $manager->division = $division->name;
            $manager->save();
        }

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dibuat.');
    }

    // 4. FORM EDIT DIVISI
    public function edit(Division $division)
    {
        // Ambil kandidat ketua (yang belum kepakai + ketua saat ini)
        $usedManagerIds = Division::where('id', '!=', $division->id)->pluck('manager_id')->toArray();
        
        $candidates = User::where('role', 'ketua_divisi')
            ->whereNotIn('id', $usedManagerIds)
            ->get();

        return view('admin.divisions.edit', compact('division', 'candidates'));
    }

    // 5. UPDATE DIVISI (DIPERBAIKI)
    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|unique:divisions,name,' . $division->id,
            'manager_id' => 'required|exists:users,id',
        ]);

        // Generate Code Baru jika nama berubah
        $generatedCode = strtoupper(str_replace(' ', '-', $request->name));

        // Update data (Sertakan 'code')
        $division->update([
            'name' => $request->name,
            'code' => $generatedCode, // <--- PENAMBAHAN PENTING
            'description' => $request->description,
            'manager_id' => $request->manager_id,
        ]);

        // Update data user ketua juga agar sinkron
        $manager = User::find($request->manager_id);
        if ($manager) {
            $manager->division = $request->name;
            $manager->save();
        }

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    // 6. HAPUS DIVISI
    public function destroy(Division $division)
    {
        $division->delete();
        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus.');
    }
}