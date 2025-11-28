<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            
            // --- KODE ANDA SEBELUMNYA (TETAP ADA) ---
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            
            // --- TAMBAHAN WAJIB (AGAR TIDAK ERROR) ---
            // Kolom ini dibutuhkan oleh Controller untuk menyimpan Ketua Divisi
            $table->unsignedBigInteger('manager_id')->nullable();
            
            // Membuat relasi agar datanya valid (Foreign Key)
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};