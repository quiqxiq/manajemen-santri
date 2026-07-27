<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_akademik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('pengurus_id')->constrained('pengurus')->cascadeOnDelete();
            $table->unsignedTinyInteger('semester'); // 1 or 2
            $table->string('tahun_ajaran'); // e.g. "2025/2026"
            $table->decimal('nilai', 5, 2); // 0.00 - 100.00
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_akademik');
    }
};
