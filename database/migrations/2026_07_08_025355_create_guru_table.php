<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // Jika di database tabelnya bernama 'guru', maka tulis 'guru' di sini:
    Schema::create('guru', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('phone');
        $table->unsignedBigInteger('school_id'); // Pastikan ini sesuai dengan FK Anda
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
