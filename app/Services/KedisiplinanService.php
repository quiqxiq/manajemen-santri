<?php

namespace App\Services;

use App\Jobs\KirimNotifikasiWhatsApp;
use App\Models\KategoriPelanggaran;
use App\Models\NotifikasiLog;
use App\Models\Pelanggaran;
use App\Models\Santri;
use App\Models\WhatsAppTemplate;
use App\Settings\KedisiplinanSettings;
use App\Settings\WhatsAppSettings;

class KedisiplinanService
{
    public function catatPelanggaran(Santri $santri, KategoriPelanggaran $kategori, array $data): Pelanggaran
    {
        $poin = $kategori->poin;

        $pelanggaran = $santri->pelanggaran()->create(array_merge($data, [
            'kategori_pelanggaran_id' => $kategori->id,
            'poin' => $poin,
        ]));

        $this->evaluasiPoinSantri($santri, $pelanggaran);

        return $pelanggaran;
    }

    public function evaluasiPoinSantri(Santri $santri, Pelanggaran $pelanggaran): void
    {
        $settings = app(KedisiplinanSettings::class);
        $totalPoin = $santri->totalPoinPelanggaran();

        $status = 'normal';
        if ($totalPoin >= $settings->poin_kritis) {
            $status = 'perlu_tindakan';
        } elseif ($totalPoin >= $settings->poin_peringatan) {
            $status = 'peringatan';
        }

        $pelanggaran->update(['status' => $status]);

        // Pemicu Notifikasi R2
        if ($totalPoin >= $settings->poin_peringatan) {
            $this->kirimNotifikasiWali($santri, $pelanggaran, $totalPoin);
        }
    }

    private function kirimNotifikasiWali(Santri $santri, Pelanggaran $pelanggaran, int $totalPoin): void
    {
        $whatsAppSettings = app(WhatsAppSettings::class);

        $template = WhatsAppTemplate::query()
            ->where('nama', $whatsAppSettings->template_pelanggaran)
            ->where('aktif', true)
            ->first();

        foreach ($santri->waliSantri as $wali) {
            $pesan = $template
                ? $template->render([
                    'nama_santri' => $santri->nama_lengkap,
                    'nama_kategori' => $pelanggaran->kategoriPelanggaran->nama_kategori ?? $pelanggaran->deskripsi,
                    'poin' => $pelanggaran->poin,
                    'total_poin' => $totalPoin,
                    'nama_wali' => $wali->nama,
                ])
                : sprintf(
                    'Pemberitahuan Pelanggaran: Santri %s mencatatkan pelanggaran "%s" (+%d poin). Total akumulasi poin saat ini: %d.',
                    $santri->nama_lengkap,
                    $pelanggaran->kategoriPelanggaran->nama_kategori ?? $pelanggaran->deskripsi,
                    $pelanggaran->poin,
                    $totalPoin
                );

            $log = NotifikasiLog::create([
                'wali_santri_id' => $wali->id,
                'pelanggaran_id' => $pelanggaran->id,
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            KirimNotifikasiWhatsApp::dispatch($log->id);
        }
    }
}
