<?php

namespace App\Services;

use App\Models\KategoriPelanggaran;
use App\Models\NotifikasiLog;
use App\Models\Pelanggaran;
use App\Models\Santri;
use App\Settings\KedisiplinanSettings;

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
        foreach ($santri->waliSantri as $wali) {
            $pesan = sprintf(
                'Pemberitahuan Pelanggaran: Santri %s mencatatkan pelanggaran "%s" (+%d poin). Total akumulasi poin saat ini: %d.',
                $santri->nama_lengkap,
                $pelanggaran->kategoriPelanggaran->nama_kategori ?? $pelanggaran->deskripsi,
                $pelanggaran->poin,
                $totalPoin
            );

            NotifikasiLog::create([
                'wali_santri_id' => $wali->id,
                'pelanggaran_id' => $pelanggaran->id,
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);
        }
    }
}
