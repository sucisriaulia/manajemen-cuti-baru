<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    // Izinkan kolom ini diisi
    protected $fillable = ['name', 'holiday_date'];

    // Ubah format tanggal agar dikenali sebagai Date
    protected $casts = [
        'holiday_date' => 'date',
    ];
}