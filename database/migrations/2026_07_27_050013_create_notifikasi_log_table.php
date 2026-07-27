<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wali_santri_id')->constrained('wali_santri')->cascadeOnDelete();
            $table->foreignId('pelanggaran_id')->nullable()->constrained('pelanggaran')->nullOnDelete();
            $table->enum('channel', ['whatsapp'])->default('whatsapp');
            $table->text('pesan');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi_log');
    }
};
