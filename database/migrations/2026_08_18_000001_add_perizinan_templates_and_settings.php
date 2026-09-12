<?php

use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Template WhatsApp untuk notifikasi perizinan:
 *  - perizinan_diajukan  : notifikasi ke petugas keamanan/pengurus saat ada izin baru diajukan
 *  - perizinan_disetujui : notifikasi ke wali santri saat perizinan disetujui
 *  - perizinan_ditolak   : notifikasi ke wali santri saat perizinan ditolak
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        DB::table('whatsapp_templates')->insertOrIgnore([
            [
                'nama' => 'perizinan_diajukan',
                'judul' => 'Pengajuan Perizinan Baru (Ke Keamanan)',
                'pesan' => "*Pengajuan Perizinan Santri Baru*\n\nAssalamu'alaikum Warahmatullahi Wabarakatuh,\nYth. Petugas Keamanan / Pengurus,\n\nTerdapat pengajuan perizinan baru dengan rincian:\n• *Nama Santri*: {nama_santri} ({kamar_santri})\n• *Wali / Pemohon*: {nama_wali} ({no_hp_wali})\n• *Jenis Izin*: {jenis_izin}\n• *Periode*: {tanggal_mulai} s.d. {tanggal_selesai} ({durasi_hari} hari)\n• *Alasan*: {alasan}\n\nMohon untuk segera memeriksa dan memproses persetujuan pada sistem.\n\nTerima kasih.",
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'perizinan_disetujui',
                'judul' => 'Perizinan Disetujui (Ke Wali Santri)',
                'pesan' => "Assalamu'alaikum Warahmatullahi Wabarakatuh,\nBapak/Ibu {nama_wali},\n\nAlhamdulillah, pengajuan perizinan untuk santri:\n• *Nama Santri*: {nama_santri}\n• *Jenis Izin*: {jenis_izin}\n• *Periode*: {tanggal_mulai} s.d. {tanggal_selesai}\n\nTelah *DISETUJUI* oleh {disetujui_oleh}.\nHarap santri kembali ke pondok pesantren tepat waktu sesuai jadwal.\n\nWassalamu'alaikum Warahmatullahi Wabarakatuh,\nPondok Pesantren Miftahul Ihsan",
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'perizinan_ditolak',
                'judul' => 'Perizinan Ditolak (Ke Wali Santri)',
                'pesan' => "Assalamu'alaikum Warahmatullahi Wabarakatuh,\nBapak/Ibu {nama_wali},\n\nMohon maaf, pengajuan perizinan untuk santri:\n• *Nama Santri*: {nama_santri}\n• *Jenis Izin*: {jenis_izin}\n• *Periode*: {tanggal_mulai} s.d. {tanggal_selesai}\n\n*DITOLAK*.\n• *Alasan Penolakan*: {alasan_penolakan}\n\nUntuk informasi lebih lanjut, silakan hubungi pihak pondok pesantren.\n\nWassalamu'alaikum Warahmatullahi Wabarakatuh,\nPondok Pesantren Miftahul Ihsan",
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->migrator->add('whatsapp.template_perizinan_diajukan', 'perizinan_diajukan');
        $this->migrator->add('whatsapp.template_perizinan_disetujui', 'perizinan_disetujui');
        $this->migrator->add('whatsapp.template_perizinan_ditolak', 'perizinan_ditolak');
        $this->migrator->add('whatsapp.nomor_wa_keamanan', '');
    }

    public function down(): void
    {
        DB::table('whatsapp_templates')
            ->whereIn('nama', ['perizinan_diajukan', 'perizinan_disetujui', 'perizinan_ditolak'])
            ->delete();

        $this->migrator->delete('whatsapp.template_perizinan_diajukan');
        $this->migrator->delete('whatsapp.template_perizinan_disetujui');
        $this->migrator->delete('whatsapp.template_perizinan_ditolak');
        $this->migrator->delete('whatsapp.nomor_wa_keamanan');
    }
};
