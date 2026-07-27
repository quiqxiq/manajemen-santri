<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\Tagihan;

class PembayaranService
{
    public function catatPembayaran(Tagihan $tagihan, array $data): Pembayaran
    {
        $pembayaran = $tagihan->pembayaran()->create(array_merge($data, [
            'santri_id' => $tagihan->santri_id,
        ]));

        $this->updateStatusTagihan($tagihan);

        return $pembayaran;
    }

    public function updateStatusTagihan(Tagihan $tagihan): void
    {
        $totalBayar = $tagihan->totalDibayar();
        $nominal = (float) $tagihan->nominal;

        if ($totalBayar >= $nominal) {
            $tagihan->update(['status' => 'lunas']);
        } elseif ($totalBayar > 0) {
            $tagihan->update(['status' => 'sebagian']);
        } else {
            $tagihan->update(['status' => 'belum_lunas']);
        }
    }
}
