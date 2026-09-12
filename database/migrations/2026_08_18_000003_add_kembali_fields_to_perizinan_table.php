<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Pembaruan tabel perizinan untuk bukti kembali:
 *  - tanggal_kembali : waktu kedatangan santri kembali di pondok
 *  - catatan_kembali : catatan laporan kedatangan dari wali / keamanan
 *  - template WhatsApp perizinan_kembali saat wali mengunggah bukti kedatangan
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        Schema::table('perizinan', function (Blueprint $table) {
            $table->dateTime('tanggal_kembali')->nullable()->after('tanggal_selesai');
            $table->text('catatan_kembali')->nullable()->after('catatan_penolakan');
        });

        DB::table('whatsapp_templates')->insertOrIgnore([
            [
                'nama' => 'perizinan_kembali',
                'judul' => 'Santri Kembali Ke Pondok (Ke Petugas Keamanan)',
                'pesan' => "*Laporan Santri Kembali ke Pondok*\n\nAssalamu'alaikum Warahmatullahi Wabarakatuh,\nYth. Petugas Keamanan / Pengurus,\n\nSantri berikut telah dilaporkan tiba / kembali ke pondok pesantren:\n• *Nama Santri*: {nama_santri} ({kamar_santri})\n• *Wali / Pelapor*: {nama_wali} ({no_hp_wali})\n• *Waktu Kedatangan*: {tanggal_kembali}\n• *Catatan*: {catatan_kembali}\n\nFoto bukti kedatangan telah diunggah ke sistem. Silakan periksa pada menu Pengajuan Perizinan.\n\nTerima kasih.",
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->migrator->add('whatsapp.template_perizinan_kembali', 'perizinan_kembali');
    }

    public function down(): void
    {
        Schema::table('perizinan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kembali', 'catatan_kembali']);
        });

        DB::table('whatsapp_templates')
            ->where('nama', 'perizinan_kembali')
            ->delete();

        $this->migrator->delete('whatsapp.template_perizinan_kembali');
    }
};
