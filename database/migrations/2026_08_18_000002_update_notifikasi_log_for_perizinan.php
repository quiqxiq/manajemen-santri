<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pembaruan tabel notifikasi_log:
 *  - wali_santri_id dijadikan nullable (agar log bisa mencatat notifikasi ke petugas/keamanan atau nomor tujuan khusus)
 *  - perizinan_id ditambahkan untuk relasi notifikasi perizinan
 *  - pengurus_id ditambahkan jika penerima adalah staf/pengurus pondok
 *  - no_hp_tujuan & nama_tujuan ditambahkan untuk mencatat nomor dan nama penerima secara langsung
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifikasi_log', function (Blueprint $table) {
            $table->foreignId('wali_santri_id')->nullable()->change();
            $table->foreignId('perizinan_id')
                ->nullable()
                ->after('tagihan_id')
                ->constrained('perizinan')
                ->nullOnDelete();
            $table->foreignId('pengurus_id')
                ->nullable()
                ->after('perizinan_id')
                ->constrained('pengurus')
                ->nullOnDelete();
            $table->string('no_hp_tujuan')->nullable()->after('pesan');
            $table->string('nama_tujuan')->nullable()->after('no_hp_tujuan');
        });
    }

    public function down(): void
    {
        Schema::table('notifikasi_log', function (Blueprint $table) {
            $table->dropConstrainedForeignId('perizinan_id');
            $table->dropConstrainedForeignId('pengurus_id');
            $table->dropColumn(['no_hp_tujuan', 'nama_tujuan']);
        });
    }
};
