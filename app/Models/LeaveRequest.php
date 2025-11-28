<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'address_during_leave', // Pastikan nama ini sesuai database
        'emergency_contact',
        'status',
        'rejection_reason',
        'doctor_note',          // TAMBAHAN: Kolom surat dokter
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateWorkDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $workDays = 0;

        while ($start->lte($end)) {
            if ($start->isWeekday()) {
                $workDays++;
            }
            $start->addDay();
        }

        return $workDays;
    }
}