<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah kolom division sudah ada
        if (!Schema::hasColumn('users', 'division')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('division')->nullable()->after('role');
            });
        }
        
        // Update enum role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'hrd', 'ketua_divisi', 'karyawan') DEFAULT 'karyawan'");
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'division')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('division');
            });
        }
        
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'hrd', 'karyawan') DEFAULT 'karyawan'");
    }
};