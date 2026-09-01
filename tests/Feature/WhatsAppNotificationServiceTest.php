<?php

namespace Tests\Feature;

use App\Models\NotifikasiLog;
use App\Models\User;
use App\Models\WaliSantri;
use App\Services\WhatsAppNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kstmostofa\LaravelWhatsApp\Exceptions\SidecarException;
use Kstmostofa\LaravelWhatsApp\Web\WebClient;
use Kstmostofa\LaravelWhatsApp\Web\WebSession;
use Mockery;
use Tests\TestCase;

class WhatsAppNotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    private function buatLog(string $noHp = '081234567890', string $status = 'pending'): NotifikasiLog
    {
        $user = User::factory()->create(['username' => 'wali-'.uniqid()]);
        $wali = WaliSantri::create([
            'user_id' => $user->id,
            'nama' => 'Bapak Ahmad',
            'no_hp' => $noHp,
        ]);

        return NotifikasiLog::create([
            'wali_santri_id' => $wali->id,
            'channel' => 'whatsapp',
            'pesan' => 'Pesan notifikasi',
            'status' => $status,
        ]);
    }

    private function mockWebClient(): WebClient
    {
        $webClient = Mockery::mock(WebClient::class);
        $webClient->shouldReceive('session')
            ->andReturnUsing(fn (string $id): WebSession => new WebSession($webClient, $id));
        $this->app->instance(WebClient::class, $webClient);

        return $webClient;
    }

    public function test_kirim_sukses_memperbarui_status_log(): void
    {
        $log = $this->buatLog();

        $webClient = $this->mockWebClient();
        $webClient->shouldReceive('request')
            ->once()
            ->with('POST', 'sessions/main/messages', Mockery::on(fn (array $options) => ($options['json']['to'] ?? null) === '6281234567890'))
            ->andReturn(['id' => 'wa-123']);

        app(WhatsAppNotificationService::class)->kirimNotifikasi($log);

        $log->refresh();
        $this->assertSame('sent', $log->status);
        $this->assertSame(1, $log->attempts);
        $this->assertSame('wa-123', $log->wa_message_id);
        $this->assertNotNull($log->sent_at);
        $this->assertNull($log->error_message);
    }

    public function test_kirim_gagal_memperbarui_status_dan_melempar_exception(): void
    {
        $log = $this->buatLog();

        $webClient = $this->mockWebClient();
        $webClient->shouldReceive('request')->once()->andThrow(new SidecarException('session not ready', 409));

        try {
            app(WhatsAppNotificationService::class)->kirimNotifikasi($log);
            $this->fail('Seharusnya melempar SidecarException');
        } catch (SidecarException $e) {
            $this->assertSame('session not ready', $e->getMessage());
        }

        $log->refresh();
        $this->assertSame('failed', $log->status);
        $this->assertSame(1, $log->attempts);
        $this->assertStringContainsString('session not ready', (string) $log->error_message);
    }

    public function test_kirim_tanpa_no_hp_menandai_gagal_tanpa_request(): void
    {
        $log = $this->buatLog(noHp: '');

        $webClient = $this->mockWebClient();
        $webClient->shouldNotReceive('request');

        app(WhatsAppNotificationService::class)->kirimNotifikasi($log);

        $log->refresh();
        $this->assertSame('failed', $log->status);
        $this->assertSame(1, $log->attempts);
        $this->assertStringContainsString('tidak tersedia', (string) $log->error_message);
    }
}
