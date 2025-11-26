<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. UPDATE TABEL USERS
        // Kita cek dulu: Kalau kolom 'phone' BELUM ada, baru kita buat.
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'address')) {
                    $table->text('address')->nullable()->after('phone');
                }
            });
        }

        // 2. UPDATE TABEL LEAVE_REQUESTS
        // Kita cek satu per satu agar tidak eror jika sebagian sudah ada
        if (Schema::hasTable('leave_requests')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('leave_requests', 'leave_address')) {
                    $table->text('leave_address')->nullable();
                }
                if (!Schema::hasColumn('leave_requests', 'emergency_contact')) {
                    $table->string('emergency_contact')->nullable();
                }
                if (!Schema::hasColumn('leave_requests', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom jika rollback
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['phone', 'address']);
            });
        }

        if (Schema::hasTable('leave_requests')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->dropColumn(['leave_address', 'emergency_contact', 'rejection_reason']);
            });
        }
    }
};