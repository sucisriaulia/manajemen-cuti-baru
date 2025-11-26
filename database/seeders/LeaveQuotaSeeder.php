<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LeaveQuota;

class LeaveQuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $currentYear = date('Y');

        foreach ($users as $user) {
            // Cek apakah user sudah punya kuota untuk tahun ini
            $existingQuota = LeaveQuota::where('user_id', $user->id)
                ->where('year', $currentYear)
                ->first();

            // Jika belum ada, buat kuota baru
            if (!$existingQuota) {
                LeaveQuota::create([
                    'user_id' => $user->id,
                    'year' => $currentYear,
                    'total_days' => 12,
                    'used_days' => 0,
                    'remaining_days' => 12,
                ]);
            }
        }

        $this->command->info('Leave quotas created successfully for all users!');
    }
}