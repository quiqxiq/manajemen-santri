<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('pengurus_id')->constrained('pengurus')->cascadeOnDelete();
            $table->date('tanggal_kejadian');
            $table->text('keluhan');
            $table->decimal('suhu_tubuh', 4, 1)->nullable();
            $table->text('diagnosis_sementara')->nullable();
            $table->enum('tindakan', ['istirahat_kamar', 'pemberian_obat', 'mini_puskesmas', 'rujuk_rs']);
            $table->string('tujuan_rujukan')->nullable();
            $table->enum('status', ['dalam_perawatan', 'dirujuk', 'selesai'])->default('dalam_perawatan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kesehatan');
    }
};
