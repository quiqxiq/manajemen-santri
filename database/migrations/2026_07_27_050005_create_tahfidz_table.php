<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahfidz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('pengurus_id')->constrained('pengurus')->cascadeOnDelete();
            $table->enum('jenis', ['setoran', 'murojaah']);
            $table->string('surat');
            $table->unsignedTinyInteger('juz')->nullable();
            $table->unsignedInteger('ayat_dari')->nullable();
            $table->unsignedInteger('ayat_sampai')->nullable();
            $table->enum('status', ['lulus', 'tidak_lulus']);
            $table->text('catatan')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahfidz');
    }
};
