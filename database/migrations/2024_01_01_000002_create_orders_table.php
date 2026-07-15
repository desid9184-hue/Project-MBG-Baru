<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_pengiriman');
            $table->integer('jumlah_porsi_besar')->default(0);
            $table->integer('jumlah_porsi_kecil')->default(0);
            $table->enum('status', [
                'pending',
                'diterima',
                'diproses',
                'dikemas',
                'siap_dikirim',
                'dalam_perjalanan',
                'sampai_sekolah',
                'selesai',
                'dibatalkan'
            ])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
