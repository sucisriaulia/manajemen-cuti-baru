<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            // Cek dulu biar aman: Kalau kolom belum ada, baru dibuat
            if (!Schema::hasColumn('divisions', 'manager_id')) {
                $table->unsignedBigInteger('manager_id')->nullable()->after('description');
                
                // Tambahkan relasi (Foreign Key)
                $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            if (Schema::hasColumn('divisions', 'manager_id')) {
                $table->dropForeign(['manager_id']);
                $table->dropColumn('manager_id');
            }
        });
    }
};