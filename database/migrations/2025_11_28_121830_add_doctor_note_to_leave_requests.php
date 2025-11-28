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
        Schema::table('leave_requests', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan nama file/path surat dokter
            // Kita beri nullable() karena Cuti Tahunan tidak butuh surat ini
            $table->string('doctor_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // Menghapus kolom jika migrasi dibatalkan (rollback)
            $table->dropColumn('doctor_note');
        });
    }
};