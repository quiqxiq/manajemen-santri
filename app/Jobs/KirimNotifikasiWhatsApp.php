<?php

namespace App\Jobs;

use App\Models\NotifikasiLog;
use App\Services\WhatsAppNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class KirimNotifikasiWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Percobaan otomatis sebelum log ditandai gagal permanen. */
    public int $tries = 3;

    /** Jeda antar percobaan (detik). */
    public array $backoff = [15, 60];

    public function __construct(public int $notifikasiLogId)
    {
    }

    public function handle(WhatsAppNotificationService $service): void
    {
        $log = NotifikasiLog::query()->find($this->notifikasiLogId);

        if (! $log) {
            return;
        }

        $service->kirimNotifikasi($log);
    }

    public function failed(?Throwable $e): void
    {
        $log = NotifikasiLog::query()->find($this->notifikasiLogId);

        if (! $log || $log->status === 'sent') {
            return;
        }

        $log->update([
            'status' => 'failed',
            'error_message' => $e?->getMessage() ?? 'Gagal mengirim notifikasi WhatsApp.',
        ]);
    }
}
