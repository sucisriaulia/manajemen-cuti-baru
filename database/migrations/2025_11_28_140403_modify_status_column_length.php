<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kita ubah kolom status menjadi VARCHAR(100) agar muat menampung teks panjang
        // Gunakan DB::statement agar kompatibel dan pasti berhasil
        DB::statement("ALTER TABLE leave_requests MODIFY COLUMN status VARCHAR(100) NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu diapa-apakan
    }
};