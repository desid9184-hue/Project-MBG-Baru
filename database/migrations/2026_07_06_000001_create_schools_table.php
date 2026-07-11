<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration.
     * Membuat tabel baru "schools" untuk menyimpan data sekolah
     * beserta titik koordinat (latitude & longitude) untuk kebutuhan
     * live tracking & rute driver.
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah');
            $table->text('alamat')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migration (menghapus tabel jika di-rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};