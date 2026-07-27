<?php

namespace App\Services;

use App\Models\Penghargaan;
use App\Models\Santri;
use App\Models\Tahfidz;

class TahfidzService
{
    public function catatSetoran(Santri $santri, array $data): Tahfidz
    {
        $status = $data['status'] ?? 'lulus';

        $tahfidz = $santri->tahfidz()->create(array_merge($data, [
            'status' => $status,
        ]));

        if ($status === 'lulus') {
            $this->evaluasiMilestoneTahfidz($santri);
        }

        return $tahfidz;
    }

    public function evaluasiMilestoneTahfidz(Santri $santri): void
    {
        $countLulus = $santri->tahfidz()->where('status', 'lulus')->count();

        // Milestone sederhana setiap kelipatan 10 setoran lulus
        if ($countLulus > 0 && $countLulus % 10 === 0) {
            $penghargaanSudahAda = $santri->penghargaan()
                ->where('bidang', 'tahfidz')
                ->where('deskripsi', 'like', "%Pencapaian {$countLulus} setoran%")
                ->exists();

            if (! $penghargaanSudahAda) {
                $pengurus = $santri->tahfidz()->latest()->first()?->pengurus_id ?? 1;

                Penghargaan::create([
                    'santri_id' => $santri->id,
                    'pengurus_id' => $pengurus,
                    'bidang' => 'tahfidz',
                    'deskripsi' => "Penghargaan Tahfidz: Pencapaian {$countLulus} setoran hafalan lulus.",
                    'tanggal' => now(),
                ]);
            }
        }
    }
}
