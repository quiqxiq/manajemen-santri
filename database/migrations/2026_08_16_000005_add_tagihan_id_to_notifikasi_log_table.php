<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Notifikasi tagihan: log kini bisa merujuk ke tagihan (selain pelanggaran).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifikasi_log', function (Blueprint $table) {
            $table->foreignId('tagihan_id')
                ->nullable()
                ->after('pelanggaran_id')
                ->constrained('tagihan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('notifikasi_log', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tagihan_id');
        });
    }
};
