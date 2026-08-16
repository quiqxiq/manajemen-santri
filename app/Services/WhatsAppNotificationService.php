<?php

namespace App\Services;

use App\Models\NotifikasiLog;
use App\Settings\WhatsAppSettings;
use Kstmostofa\LaravelWhatsApp\Web\WebClient;
use Throwable;

class WhatsAppNotificationService
{
    public function __construct(private readonly WebClient $webClient)
    {
    }

    /**
     * ID sesi WhatsApp Web (sidecar) yang dipakai untuk pengiriman.
     */
    public function sessionId(): string
    {
        return app(WhatsAppSettings::class)->session_id;
    }

    /**
     * Kirim notifikasi via Web sidecar (whatsapp-web.js) dan perbarui status log.
     *
     * Melempar ulang exception supaya queue worker bisa melakukan retry otomatis
     * (lihat $tries / $backoff pada KirimNotifikasiWhatsApp).
     *
     * @throws Throwable
     */
    public function kirimNotifikasi(NotifikasiLog $log): void
    {
        if ($log->status === 'sent') {
            return;
        }

        $log->increment('attempts');

        $noHp = $log->waliSantri?->no_hp;

        if (blank($noHp)) {
            $log->update([
                'status' => 'failed',
                'error_message' => 'Nomor HP wali santri tidak tersedia.',
            ]);

            return;
        }

        try {
            $response = $this->webClient->session($this->sessionId())
                ->messages()
                ->sendText($noHp, $log->pesan);

            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
                'wa_message_id' => $response['id'] ?? null,
                'error_message' => null,
            ]);
        } catch (Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
