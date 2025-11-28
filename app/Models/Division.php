<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    // KITA TAMBAHKAN 'code' KE SINI
    protected $fillable = [
        'name', 
        'code',       // <--- Wajib ada agar bisa disimpan
        'description', 
        'manager_id'
    ];

    /**
     * Relasi ke User (Ketua Divisi).
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Relasi ke Anggota Divisi (Opsional/Tambahan).
     * Berguna jika nanti ingin melihat siapa saja anggota divisi ini.
     */
    public function members()
    {
        // Relasi berdasarkan nama divisi di tabel user
        return $this->hasMany(User::class, 'division', 'name'); 
    }
}