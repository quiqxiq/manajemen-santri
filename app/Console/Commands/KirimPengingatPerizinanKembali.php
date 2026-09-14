<?php

namespace App\Console\Commands;

use App\Models\Perizinan;
use App\Services\PerizinanService;
use Illuminate\Console\Command;

class KirimPengingatPerizinanKembali extends Command
{
    protected $signature = 'whatsapp:pengingat-perizinan-kembali {--days=1 : Kirim pengingat untuk perizinan yang batas kembalinya dalam N hari ke depan}';

    protected $description = 'Kirim pengingat WhatsApp ke wali santri untuk kepulangan santri yang mendekati tenggat waktu perizinan';

    public function handle(): int
    {
        $days = max(0, (int) $this->option('days'));
        $batasTanggal = now()->addDays($days)->toDateString();

        $perizinans = Perizinan::query()
            ->where('status', 'disetujui')
            ->whereNull('tanggal_kembali')
            ->whereDate('tanggal_selesai', '<=', $batasTanggal)
            ->with(['santri.waliSantri', 'santri.kamar'])
            ->get();

        $terkirim = 0;
        $dilewati = 0;
        $perizinanService = app(PerizinanService::class);

        foreach ($perizinans as $perizinan) {
            $santri = $perizinan->santri;

            if (! $santri || $santri->waliSantri->isEmpty()) {
                $dilewati++;

                continue;
            }

            // Hindari pengiriman berulang pada hari yang sama
            if ($perizinan->pengingat_kembali_sent_at && $perizinan->pengingat_kembali_sent_at->isToday()) {
                $dilewati++;

                continue;
            }

            $perizinanService->kirimNotifikasiPengingatKembali($perizinan);
            $terkirim++;
        }

        $this->info("Pengingat kepulangan santri: {$terkirim} diproses, {$dilewati} dilewati.");

        return self::SUCCESS;
    }
}
