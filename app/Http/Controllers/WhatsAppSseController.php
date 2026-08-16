<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Kstmostofa\LaravelWhatsApp\Exceptions\SidecarException;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class WhatsAppSseController extends Controller
{
    /** Event SSE yang relevan untuk halaman status sesi (pesan masuk tidak diteruskan). */
    protected const ALLOWED_EVENTS = ['hello', 'qr', 'code', 'authenticated', 'auth_failure', 'ready', 'disconnected', 'error'];

    /**
     * Snapshot status sesi (dipakai saat halaman dimuat dan setelah aksi Livewire).
     */
    public function state(string $session): JsonResponse
    {
        $client = WhatsApp::webClient();

        if (! $client->ping()) {
            return response()->json([
                'status' => 'sidecar_down',
                'error' => 'Sidecar WhatsApp tidak berjalan. Jalankan: php artisan whatsapp:sidecar:start',
            ]);
        }

        try {
            $state = WhatsApp::web($session)->state();
            $qr = WhatsApp::web($session)->qr();
        } catch (SidecarException $e) {
            $status = $e->getCode() === 404 ? 'session_not_found' : 'error';

            return response()->json([
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json(array_merge($state, [
            'qr' => $qr['qr'] ?? null,
            'pairingCode' => $qr['pairingCode'] ?? null,
        ]));
    }

    /**
     * Proxy stream Server-Sent Events dari sidecar ke browser, terautentikasi
     * via sesi web. Status sesi tampil live tanpa polling.
     */
    public function stream(Request $request, string $session): StreamedResponse
    {
        $client = WhatsApp::webClient();

        $stream = new StreamedResponse(function () use ($client, $session) {
            set_time_limit(0);

            try {
                $http = new GuzzleClient([
                    'base_uri' => sprintf('http://%s:%d/', $client->host(), $client->port()),
                    'timeout' => 0,
                    'http_errors' => false,
                    'headers' => array_filter([
                        'Accept' => 'text/event-stream',
                        'Authorization' => $client->token() ? 'Bearer '.$client->token() : null,
                    ]),
                ]);

                $res = $http->request('GET', 'sessions/'.rawurlencode($session).'/events', ['stream' => true]);
            } catch (GuzzleException $e) {
                $this->writeEvent('sidecar_down', ['error' => 'Sidecar tidak dapat dijangkau: '.$e->getMessage()]);

                return;
            }

            if ($res->getStatusCode() >= 400) {
                $this->writeEvent('sidecar_error', ['error' => 'Sidecar menolak sesi (HTTP '.$res->getStatusCode().').']);

                return;
            }

            $body = $res->getBody();
            $buffer = '';
            $lastWrite = microtime(true);

            while (! $body->eof()) {
                if (connection_aborted()) {
                    break;
                }

                $chunk = $body->read(1024);
                if ($chunk === '') {
                    // Jaga koneksi tetap hidup + cegah busy-loop.
                    if (microtime(true) - $lastWrite > 20) {
                        echo ": ping\n\n";
                        @ob_flush();
                        flush();
                        $lastWrite = microtime(true);
                    }
                    usleep(100_000);
                    continue;
                }

                $buffer .= $chunk;

                while (($pos = strpos($buffer, "\n\n")) !== false) {
                    $frame = substr($buffer, 0, $pos);
                    $buffer = substr($buffer, $pos + 2);

                    $event = null;
                    foreach (explode("\n", $frame) as $line) {
                        if (str_starts_with($line, 'event:')) {
                            $event = trim(substr($line, 6));
                            break;
                        }
                    }

                    if (in_array($event, self::ALLOWED_EVENTS, true)) {
                        echo $frame."\n\n";
                        @ob_flush();
                        flush();
                        $lastWrite = microtime(true);
                    }
                }

                // Amankan buffer dari frame raksasa.
                if (strlen($buffer) > 65536) {
                    $buffer = substr($buffer, -65536);
                }
            }
        });

        $stream->headers->set('Content-Type', 'text/event-stream');
        $stream->headers->set('Cache-Control', 'no-cache');
        $stream->headers->set('X-Accel-Buffering', 'no');
        $stream->headers->set('Connection', 'keep-alive');

        return $stream;
    }

    private function writeEvent(string $event, array $data): void
    {
        echo "event: {$event}\n";
        echo 'data: '.json_encode($data)."\n\n";
        @ob_flush();
        flush();
    }
}
