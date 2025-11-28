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
        Schema::table('users', function (Blueprint $table) {
            // Kita cek dulu biar tidak error kalau kolomnya ternyata sudah ada
            if (!Schema::hasColumn('users', 'annual_leave_balance')) {
                // Tambahkan kolom integer dengan nilai default 12
                $table->integer('annual_leave_balance')->default(12)->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('annual_leave_balance');
        });
    }
};