<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nis')->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->string('asal_sekolah')->nullable();
            $table->foreignId('kamar_id')->nullable()->constrained('kamar')->nullOnDelete();
            $table->enum('status', ['aktif', 'nonaktif', 'lulus', 'keluar'])->default('aktif');
            $table->date('tanggal_masuk');
            $table->timestamps();

            $table->index(['status', 'kamar_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santri');
    }
};
