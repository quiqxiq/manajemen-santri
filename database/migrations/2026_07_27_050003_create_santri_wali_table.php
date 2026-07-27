<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('santri_wali', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('wali_santri_id')->constrained('wali_santri')->cascadeOnDelete();
            $table->enum('hubungan', ['ayah', 'ibu', 'wali_lain']);
            $table->boolean('is_penanggung_jawab_utama')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santri_wali');
    }
};
