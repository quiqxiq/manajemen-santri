<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Template WhatsApp untuk notifikasi tagihan (hanya pelanggaran & tagihan
 * yang memicu notifikasi ke wali santri).
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        DB::table('whatsapp_templates')->insertOrIgnore([
            'nama' => 'tagihan',
            'judul' => 'Pemberitahuan Tagihan',
            'pesan' => "Pemberitahuan Tagihan:\nAnanda {nama_santri} memiliki tagihan {jenis_tagihan} sebesar Rp{nominal} untuk bulan {bulan}/{tahun}, jatuh tempo {jatuh_tempo}.\n\nHormat kami,\nPondok Pesantren",
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->migrator->add('whatsapp.template_tagihan', 'tagihan');
    }

    public function down(): void
    {
        DB::table('whatsapp_templates')->where('nama', 'tagihan')->delete();
        $this->migrator->delete('whatsapp.template_tagihan');
    }
};
