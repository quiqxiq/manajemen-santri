<?php

namespace App\Console\Commands;

use App\Jobs\KirimNotifikasiWhatsApp;
use App\Models\NotifikasiLog;
use App\Models\Tagihan;
use App\Models\WhatsAppTemplate;
use App\Settings\WhatsAppSettings;
use Illuminate\Console\Command;

class KirimPengingatJatuhTempo extends Command
{
    protected $signature = 'whatsapp:pengingat-jatuh-tempo {--days=0 : Kirim pengingat untuk tagihan yang jatuh tempo dalam N hari ke depan}';

    protected $description = 'Kirim pengingat WhatsApp ke wali santri untuk tagihan yang jatuh tempo dan belum lunas';

    public function handle(): int
    {
        $days = max(0, (int) $this->option('days'));
        $batas = now()->addDays($days)->endOfDay();

        $settings = app(WhatsAppSettings::class);
        $template = WhatsAppTemplate::query()
            ->where('nama', $settings->template_jatuh_tempo)
            ->where('aktif', true)
            ->first();

        $tagihans = Tagihan::query()
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->where('jatuh_tempo', '<=', $batas)
            ->with('santri.waliSantri')
            ->get();

        $terkirim = 0;
        $dilewati = 0;

        foreach ($tagihans as $tagihan) {
            $santri = $tagihan->santri;

            if (! $santri || $santri->waliSantri->isEmpty()) {
                $dilewati++;

                continue;
            }

            $nominal = number_format((float) $tagihan->nominal, 0, ',', '.');
            $sisa = number_format($tagihan->sisaTagihan(), 0, ',', '.');
            $jatuhTempo = $tagihan->jatuh_tempo?->format('d/m/Y');

            foreach ($santri->waliSantri as $wali) {
                $pesan = $template
                    ? $template->render([
                        'nama_santri' => $santri->nama_lengkap,
                        'nama_wali' => $wali->nama,
                        'jenis_tagihan' => $tagihan->jenis,
                        'nominal' => $nominal,
                        'sisa_tagihan' => $sisa,
                        'jatuh_tempo' => $jatuhTempo,
                    ])
                    : sprintf(
                        'Pengingat: tagihan %s Ananda %s sebesar Rp%s jatuh tempo pada %s dan belum lunas. Sisa: Rp%s.',
                        $tagihan->jenis,
                        $santri->nama_lengkap,
                        $nominal,
                        $jatuhTempo,
                        $sisa
                    );

                // Hindari pengiriman ganda untuk tagihan yang sama pada hari yang sama.
                $sudahAda = NotifikasiLog::query()
                    ->where('tagihan_id', $tagihan->id)
                    ->where('pesan', $pesan)
                    ->whereDate('created_at', today())
                    ->exists();

                if ($sudahAda) {
                    $dilewati++;

                    continue;
                }

                $log = NotifikasiLog::create([
                    'wali_santri_id' => $wali->id,
                    'tagihan_id' => $tagihan->id,
                    'channel' => 'whatsapp',
                    'pesan' => $pesan,
                    'status' => 'pending',
                ]);

                KirimNotifikasiWhatsApp::dispatch($log->id);

                $terkirim++;
            }
        }

        $this->info("Pengingat jatuh tempo: {$terkirim} dikirim, {$dilewati} dilewati.");

        return self::SUCCESS;
    }
}
