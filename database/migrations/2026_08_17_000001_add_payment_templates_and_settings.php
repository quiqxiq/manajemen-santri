<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Template WhatsApp untuk notifikasi pembayaran:
 *  - pembayaran_diterima : konfirmasi saat pembayaran dicatat
 *  - tagihan_lunas       : konfirmasi saat tagihan lunas
 *  - tagihan_jatuh_tempo : pengingat otomatis saat jatuh tempo
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        DB::table('whatsapp_templates')->insertOrIgnore([
            [
                'nama' => 'pembayaran_diterima',
                'judul' => 'Konfirmasi Pembayaran Diterima',
                'pesan' => "Assalamu'alaikum Bapak/Ibu {nama_wali},\n\nPembayaran Ananda {nama_santri} untuk tagihan {jenis_tagihan} sebesar Rp{nominal_bayar} telah kami terima pada {tanggal_bayar}. Sisa tagihan: Rp{sisa_tagihan}.\n\nHormat kami,\nPondok Pesantren",
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'tagihan_lunas',
                'judul' => 'Tagihan Lunas',
                'pesan' => "Assalamu'alaikum Bapak/Ibu {nama_wali},\n\nAlhamdulillah, tagihan {jenis_tagihan} Ananda {nama_santri} sebesar Rp{nominal} telah LUNAS. Terima kasih atas pembayarannya.\n\nHormat kami,\nPondok Pesantren",
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'tagihan_jatuh_tempo',
                'judul' => 'Pengingat Tagihan Jatuh Tempo',
                'pesan' => "Assalamu'alaikum Bapak/Ibu {nama_wali},\n\nPengingat: tagihan {jenis_tagihan} Ananda {nama_santri} sebesar Rp{nominal} jatuh tempo pada {jatuh_tempo} dan masih belum lunas. Sisa tagihan: Rp{sisa_tagihan}. Mohon segera melakukan pembayaran.\n\nHormat kami,\nPondok Pesantren",
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->migrator->add('whatsapp.template_pembayaran', 'pembayaran_diterima');
        $this->migrator->add('whatsapp.template_lunas', 'tagihan_lunas');
        $this->migrator->add('whatsapp.template_jatuh_tempo', 'tagihan_jatuh_tempo');
    }

    public function down(): void
    {
        DB::table('whatsapp_templates')
            ->whereIn('nama', ['pembayaran_diterima', 'tagihan_lunas', 'tagihan_jatuh_tempo'])
            ->delete();

        $this->migrator->delete('whatsapp.template_pembayaran');
        $this->migrator->delete('whatsapp.template_lunas');
        $this->migrator->delete('whatsapp.template_jatuh_tempo');
    }
};
