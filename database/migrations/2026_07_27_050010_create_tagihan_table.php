<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->enum('jenis', ['spp', 'daftar_ulang', 'lainnya']);
            $table->unsignedTinyInteger('bulan')->nullable(); // 1 - 12
            $table->unsignedSmallInteger('tahun');
            $table->decimal('nominal', 12, 2);
            $table->enum('status', ['belum_lunas', 'sebagian', 'lunas'])->default('belum_lunas');
            $table->date('jatuh_tempo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
