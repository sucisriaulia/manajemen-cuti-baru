<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    // Menampilkan halaman daftar hari libur
    public function index()
    {
        // Ambil data hari libur, urutkan dari tanggal terbaru
        $holidays = Holiday::orderBy('holiday_date', 'desc')->get();
        return view('admin.holidays.index', compact('holidays'));
    }

    // Menyimpan hari libur baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'holiday_date' => 'required|date|unique:holidays,holiday_date',
        ]);

        Holiday::create($request->all());

        return back()->with('success', 'Hari libur berhasil ditambahkan.');
    }

    // Menghapus hari libur
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return back()->with('success', 'Hari libur dihapus.');
    }
}