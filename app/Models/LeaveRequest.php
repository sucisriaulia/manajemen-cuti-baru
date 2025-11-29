<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Holiday; // Pastikan Model Holiday di-import

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'address_during_leave',
        'emergency_contact',
        'status',
        'rejection_reason',
        'doctor_note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Menghitung hari kerja (Senin-Jumat) dikurangi Hari Libur Nasional.
     */
    public function calculateWorkDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // 1. Ambil semua tanggal libur yang ada di rentang tanggal pengajuan
        // Kita ambil format Y-m-d agar mudah dibandingkan
        $holidays = Holiday::whereBetween('holiday_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                           ->pluck('holiday_date')
                           ->map(function ($date) {
                               return Carbon::parse($date)->format('Y-m-d');
                           })
                           ->toArray();

        $workDays = 0;

        while ($start->lte($end)) {
            // Cek 1: Apakah Hari Kerja (Senin-Jumat)?
            if ($start->isWeekday()) {
                // Cek 2: Apakah tanggal ini BUKAN hari libur nasional?
                // Jika tanggal ini TIDAK ada di daftar holidays, maka hitung
                if (!in_array($start->format('Y-m-d'), $holidays)) {
                    $workDays++;
                }
            }
            $start->addDay();
        }

        return $workDays;
    }
}