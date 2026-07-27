<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama');
            $table->enum('bagian', [
                'tata_usaha',
                'keuangan',
                'keamanan',
                'akademik',
                'tahfidz',
                'kesehatan',
                'pengasuhan'
            ]);
            $table->string('no_hp')->nullable();
            $table->timestamps();
        });

        Schema::table('kamar', function (Blueprint $table) {
            $table->foreign('pengurus_pembina_id')->references('id')->on('pengurus')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            $table->dropForeign(['pengurus_pembina_id']);
        });

        Schema::dropIfExists('pengurus');
    }
};
