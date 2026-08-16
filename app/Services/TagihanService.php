<?php

namespace App\Services;

use App\Jobs\KirimNotifikasiWhatsApp;
use App\Models\NotifikasiLog;
use App\Models\Tagihan;
use App\Models\WhatsAppTemplate;
use App\Settings\WhatsAppSettings;

class TagihanService
{
    /**
     * Kirim notifikasi WhatsApp ke wali santri saat tagihan dibuat.
     * Hanya pelanggaran & tagihan yang memicu notifikasi (R).
     */
    public function kirimNotifikasiWali(Tagihan $tagihan): void
    {
        $santri = $tagihan->santri;

        if (! $santri) {
            return;
        }

        $whatsAppSettings = app(WhatsAppSettings::class);

        $template = WhatsAppTemplate::query()
            ->where('nama', $whatsAppSettings->template_tagihan)
            ->where('aktif', true)
            ->first();

        $nominal = number_format((float) $tagihan->nominal, 0, ',', '.');

        foreach ($santri->waliSantri as $wali) {
            $pesan = $template
                ? $template->render([
                    'nama_santri' => $santri->nama_lengkap,
                    'jenis_tagihan' => $tagihan->jenis,
                    'nominal' => $nominal,
                    'bulan' => $tagihan->bulan,
                    'tahun' => $tagihan->tahun,
                    'jatuh_tempo' => $tagihan->jatuh_tempo?->format('d/m/Y'),
                    'nama_wali' => $wali->nama,
                ])
                : sprintf(
                    'Pemberitahuan Tagihan: Ananda %s memiliki tagihan %s sebesar Rp%s untuk bulan %s/%s, jatuh tempo %s.',
                    $santri->nama_lengkap,
                    $tagihan->jenis,
                    $nominal,
                    $tagihan->bulan,
                    $tagihan->tahun,
                    $tagihan->jatuh_tempo?->format('d/m/Y')
                );

            $log = NotifikasiLog::create([
                'wali_santri_id' => $wali->id,
                'tagihan_id' => $tagihan->id,
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            KirimNotifikasiWhatsApp::dispatch($log->id);
        }
    }
}
