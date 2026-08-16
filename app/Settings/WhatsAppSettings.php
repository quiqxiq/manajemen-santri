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

    public static function group(): string
    {
        return 'whatsapp';
    }
}
