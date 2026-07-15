<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('lauk');
            $table->string('sayur');
            $table->string('buah');
            $table->string('sambal')->nullable();
            $table->decimal('kalori', 8, 2)->default(0);
            $table->decimal('protein', 8, 2)->default(0);
            $table->decimal('lemak', 8, 2)->default(0);
            $table->decimal('karbohidrat', 8, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
