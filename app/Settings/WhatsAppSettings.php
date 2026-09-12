<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WhatsAppSettings extends Settings
{
    /** ID sesi WhatsApp Web yang dipakai untuk mengirim notifikasi. */
    public string $session_id;

    /** Key template (kolom `nama` di tabel whatsapp_templates) untuk notifikasi pelanggaran. */
    public string $template_pelanggaran;

    /** Key template untuk notifikasi tagihan. */
    public string $template_tagihan;

    /** Key template untuk konfirmasi pembayaran diterima. */
    public string $template_pembayaran;

    /** Key template untuk notifikasi tagihan lunas. */
    public string $template_lunas;

    /** Key template untuk pengingat tagihan jatuh tempo. */
    public string $template_jatuh_tempo;

    /** Key template untuk notifikasi pengajuan perizinan ke petugas keamanan. */
    public string $template_perizinan_diajukan;

    /** Key template untuk notifikasi perizinan disetujui ke wali santri. */
    public string $template_perizinan_disetujui;

    /** Key template untuk notifikasi perizinan ditolak ke wali santri. */
    public string $template_perizinan_ditolak;

    /** Key template untuk notifikasi santri kembali ke pondok ke petugas keamanan. */
    public string $template_perizinan_kembali;

    /** Nomor kontak WhatsApp keamanan (opsional / fallback). */
    public ?string $nomor_wa_keamanan;

    public static function group(): string
    {
        return 'whatsapp';
    }
}
