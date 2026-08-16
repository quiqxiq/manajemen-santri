<?php

namespace App\Filament\Pages;

use App\Settings\WhatsAppSettings;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;
use Throwable;
use UnitEnum;

class KelolaWhatsApp extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string|UnitEnum|null $navigationGroup = 'Sistem & Pengaturan';

    protected static ?string $navigationLabel = 'WhatsApp Gateway';

    protected static ?string $title = 'WhatsApp Gateway';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'whatsapp-gateway';

    protected string $view = 'filament.pages.kelola-whatsapp';

    /** ID sesi WhatsApp Web (sidecar) yang sedang dikelola. */
    public ?string $sessionId = 'main';

    public function mount(): void
    {
        $this->sessionId = app(WhatsAppSettings::class)->session_id;
    }

    public function saveSessionId(string $sessionId): void
    {
        $sessionId = trim($sessionId);

        if ($sessionId === '' || ! preg_match('/^[A-Za-z0-9._-]+$/', $sessionId)) {
            Notification::make()
                ->title('ID sesi tidak valid')
                ->body('Hanya huruf, angka, titik, garis bawah, dan strip yang diperbolehkan.')
                ->danger()
                ->send();

            return;
        }

        $settings = app(WhatsAppSettings::class);
        $settings->session_id = $sessionId;
        $settings->save();

        $this->sessionId = $sessionId;
        $this->dispatch('wa-refresh');

        Notification::make()
            ->title('Sesi WhatsApp tersimpan')
            ->body("Pengiriman notifikasi sekarang memakai sesi \"{$sessionId}\".")
            ->success()
            ->send();
    }

    public function startSession(): void
    {
        try {
            WhatsApp::web($this->sessionId)->start();
            $this->dispatch('wa-refresh');
            Notification::make()
                ->title('Sesi WhatsApp dimulai')
                ->body('Pindai QR atau gunakan kode pairing untuk menautkan nomor.')
                ->info()
                ->send();
        } catch (Throwable $e) {
            $this->gagal('Gagal memulai sesi', $e);
        }
    }

    public function stopSession(): void
    {
        try {
            WhatsApp::web($this->sessionId)->stop();
            $this->dispatch('wa-refresh');
            Notification::make()
                ->title('Sesi dihentikan')
                ->body('Status autentikasi tetap tersimpan; start berikutnya tidak perlu QR lagi.')
                ->success()
                ->send();
        } catch (Throwable $e) {
            $this->gagal('Gagal menghentikan sesi', $e);
        }
    }

    public function destroySession(): void
    {
        try {
            WhatsApp::web($this->sessionId)->destroy();
            $this->dispatch('wa-refresh');
            Notification::make()
                ->title('Sesi dihapus')
                ->body('Autentikasi dihapus — start berikutnya akan menampilkan QR baru.')
                ->success()
                ->send();
        } catch (Throwable $e) {
            $this->gagal('Gagal menghapus sesi', $e);
        }
    }

    public function requestPairingCode(string $phoneNumber): void
    {
        $phoneNumber = preg_replace('/\D+/', '', (string) $phoneNumber) ?? '';

        if ($phoneNumber === '') {
            Notification::make()
                ->title('Nomor HP tidak valid')
                ->body('Masukkan nomor format internasional tanpa tanda + (contoh: 6281234567890).')
                ->danger()
                ->send();

            return;
        }

        try {
            WhatsApp::web($this->sessionId)->client()->request(
                'POST',
                "sessions/{$this->sessionId}/pairing-code",
                ['json' => ['phoneNumber' => $phoneNumber, 'intervalMs' => 60000]]
            );
            $this->dispatch('wa-refresh');
            Notification::make()
                ->title('Kode pairing diminta')
                ->body('Masukkan kode di WhatsApp → Setelan → Perangkat Tertaut → "Tautkan dengan nomor telepon".')
                ->info()
                ->send();
        } catch (Throwable $e) {
            $this->gagal('Gagal meminta kode pairing', $e);
        }
    }

    private function gagal(string $title, Throwable $e): void
    {
        Notification::make()
            ->title($title)
            ->body($e->getMessage())
            ->danger()
            ->send();
    }
}
