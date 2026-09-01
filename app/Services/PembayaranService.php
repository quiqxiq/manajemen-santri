<?php

namespace App\Services;

use App\Jobs\KirimNotifikasiWhatsApp;
use App\Models\NotifikasiLog;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\WhatsAppTemplate;
use App\Settings\WhatsAppSettings;

class PembayaranService
{
    public function catatPembayaran(Tagihan $tagihan, array $data): Pembayaran
    {
        $pembayaran = $tagihan->pembayaran()->create(array_merge($data, [
            'santri_id' => $tagihan->santri_id,
        ]));

        $this->updateStatusTagihan($tagihan);

        $this->kirimNotifikasiPembayaran($pembayaran);

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

    /**
     * Kirim notifikasi WhatsApp saat pembayaran dicatat, dan (jika tagihan
     * menjadi lunas) notifikasi pelunasan. Tidak mengirim apa pun bila santri
     * tidak memiliki wali.
     */
    public function kirimNotifikasiPembayaran(Pembayaran $pembayaran): void
    {
        $tagihan = $pembayaran->tagihan;
        $santri = $tagihan?->santri;

        if (! $santri || $santri->waliSantri()->count() === 0) {
            return;
        }

        $whatsAppSettings = app(WhatsAppSettings::class);

        $nominalBayar = number_format((float) $pembayaran->jumlah_bayar, 0, ',', '.');
        $sisa = number_format($tagihan->sisaTagihan(), 0, ',', '.');
        $tanggalBayar = $pembayaran->tanggal_bayar?->format('d/m/Y') ?? now()->format('d/m/Y');

        $template = WhatsAppTemplate::query()
            ->where('nama', $whatsAppSettings->template_pembayaran)
            ->where('aktif', true)
            ->first();

        foreach ($santri->waliSantri as $wali) {
            $pesan = $template
                ? $template->render([
                    'nama_santri' => $santri->nama_lengkap,
                    'nama_wali' => $wali->nama,
                    'jenis_tagihan' => $tagihan->jenis,
                    'nominal_bayar' => $nominalBayar,
                    'sisa_tagihan' => $sisa,
                    'tanggal_bayar' => $tanggalBayar,
                    'metode_pembayaran' => $pembayaran->metode_pembayaran ?? '-',
                ])
                : sprintf(
                    'Pembayaran Ananda %s untuk tagihan %s sebesar Rp%s telah diterima. Sisa tagihan: Rp%s.',
                    $santri->nama_lengkap,
                    $tagihan->jenis,
                    $nominalBayar,
                    $sisa
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

        if ($tagihan->status === 'lunas') {
            $this->kirimNotifikasiLunas($tagihan);
        }
    }

    private function kirimNotifikasiLunas(Tagihan $tagihan): void
    {
        $santri = $tagihan->santri;

        if (! $santri || $santri->waliSantri()->count() === 0) {
            return;
        }

        $whatsAppSettings = app(WhatsAppSettings::class);
        $nominal = number_format((float) $tagihan->nominal, 0, ',', '.');

        $template = WhatsAppTemplate::query()
            ->where('nama', $whatsAppSettings->template_lunas)
            ->where('aktif', true)
            ->first();

        foreach ($santri->waliSantri as $wali) {
            $pesan = $template
                ? $template->render([
                    'nama_santri' => $santri->nama_lengkap,
                    'nama_wali' => $wali->nama,
                    'jenis_tagihan' => $tagihan->jenis,
                    'nominal' => $nominal,
                ])
                : sprintf(
                    'Tagihan %s Ananda %s sebesar Rp%s telah LUNAS. Terima kasih.',
                    $tagihan->jenis,
                    $santri->nama_lengkap,
                    $nominal
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
