<?php

namespace App\Services;

use App\Domain\Rules\Contracts\BusinessRule;
use App\Domain\Rules\Perizinan\TidakAdaTunggakanRule;
use App\Exceptions\RuleViolationException;
use App\Models\Perizinan;
use App\Models\Santri;

class PerizinanService
{
    /** @var BusinessRule[] */
    private array $rules;

    public function __construct(?array $rules = null)
    {
        $this->rules = $rules ?? [new TidakAdaTunggakanRule()];
    }

    public function validateAndCreate(Santri $santri, array $data): Perizinan
    {
        foreach ($this->rules as $rule) {
            if (! $rule->passes($santri)) {
                throw new RuleViolationException($rule->message());
            }
        }

        return $santri->perizinan()->create(array_merge($data, [
            'status' => 'diajukan',
        ]));
    }

    public function checkCanApply(Santri $santri): ?string
    {
        foreach ($this->rules as $rule) {
            if (! $rule->passes($santri)) {
                return $rule->message();
            }
        }

        return null;
    }

    /**
     * Ambil daftar seluruh nomor WhatsApp dan pengurus petugas keamanan.
     * Menggabungkan semua pengurus bagian keamanan aktif, user role Keamanan, dan hotline di pengaturan.
     *
     * @return array<int, array{pengurus_id: ?int, no_hp: string, nama: string}>
     */
    public function getDaftarPenerimaKeamanan(): array
    {
        $whatsAppSettings = app(\App\Settings\WhatsAppSettings::class);
        $penerima = [];
        $nomorTerkirim = [];

        // 1. Ambil pengurus bagian keamanan yang terhubung dengan akun aktif (atau tanpa akun login khusus)
        $pengurusKeamanan = \App\Models\Pengurus::query()
            ->where('bagian', 'keamanan')
            ->whereNotNull('no_hp')
            ->where('no_hp', '!=', '')
            ->where(function ($query) {
                $query->whereDoesntHave('user')
                    ->orWhereHas('user', fn ($u) => $u->where('is_active', true));
            })
            ->get();

        foreach ($pengurusKeamanan as $petugas) {
            $digits = preg_replace('/\D+/', '', $petugas->no_hp);
            if (! empty($digits) && ! in_array($digits, $nomorTerkirim, true)) {
                $nomorTerkirim[] = $digits;
                $penerima[] = [
                    'pengurus_id' => $petugas->id,
                    'no_hp' => $petugas->no_hp,
                    'nama' => $petugas->nama,
                ];
            }
        }

        // 2. Ambil user dengan role Keamanan yang belum masuk di daftar pengurus
        try {
            $usersKeamanan = \App\Models\User::role('Keamanan')
                ->where('is_active', true)
                ->with('pengurus')
                ->get();

            foreach ($usersKeamanan as $user) {
                if ($user->pengurus && ! empty($user->pengurus->no_hp)) {
                    $digits = preg_replace('/\D+/', '', $user->pengurus->no_hp);
                    if (! in_array($digits, $nomorTerkirim, true)) {
                        $nomorTerkirim[] = $digits;
                        $penerima[] = [
                            'pengurus_id' => $user->pengurus->id,
                            'no_hp' => $user->pengurus->no_hp,
                            'nama' => $user->pengurus->nama ?: $user->name,
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            // Abaikan jika Spatie roles belum terpasang di konteks tertentu
        }

        // 3. Ambil nomor dari WhatsApp Settings (mendukung multi-nomor dipisahkan koma / titik koma / baris baru)
        if (! empty($whatsAppSettings->nomor_wa_keamanan)) {
            $rawList = preg_split('/[\r\n,;]+/', $whatsAppSettings->nomor_wa_keamanan);
            foreach ($rawList as $idx => $rawNomor) {
                $rawNomor = trim($rawNomor);
                $digits = preg_replace('/\D+/', '', $rawNomor);
                if (! empty($digits) && ! in_array($digits, $nomorTerkirim, true)) {
                    $nomorTerkirim[] = $digits;
                    $penerima[] = [
                        'pengurus_id' => null,
                        'no_hp' => $rawNomor,
                        'nama' => 'Piket Keamanan (Pos Pusat ' . ($idx + 1) . ')',
                    ];
                }
            }
        }

        return $penerima;
    }

    /**
     * Kirim notifikasi database (lonceng) Filament ke seluruh user Keamanan & Admin.
     */
    public function kirimDatabaseNotificationKeamanan(string $title, string $body, string $icon = 'heroicon-o-shield-check', string $color = 'info'): void
    {
        try {
            $users = \App\Models\User::role(['Keamanan', 'Admin'])->where('is_active', true)->get();
            if ($users->isNotEmpty()) {
                \Filament\Notifications\Notification::make()
                    ->title($title)
                    ->body($body)
                    ->icon($icon)
                    ->color($color)
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('buka')
                            ->label('Lihat Perizinan')
                            ->url('/admin/perizinans'),
                    ])
                    ->sendToDatabase($users);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mengirim database notification ke keamanan: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi database (lonceng) Filament ke akun user Wali Santri.
     */
    public function kirimDatabaseNotificationWali(Perizinan $perizinan, string $title, string $body, string $icon = 'heroicon-o-information-circle', string $color = 'info'): void
    {
        try {
            $santri = $perizinan->santri;
            if (! $santri) {
                return;
            }

            $waliUsers = $santri->waliSantri
                ->pluck('user')
                ->filter(fn ($u) => $u && $u->is_active);

            if ($waliUsers->isNotEmpty()) {
                \Filament\Notifications\Notification::make()
                    ->title($title)
                    ->body($body)
                    ->icon($icon)
                    ->color($color)
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('buka')
                            ->label('Buka Riwayat Izin')
                            ->url('/wali/perizinans'),
                    ])
                    ->sendToDatabase($waliUsers);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mengirim database notification ke wali: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi WhatsApp & Database ke seluruh petugas keamanan saat izin diajukan.
     */
    public function kirimNotifikasiPengajuan(Perizinan $perizinan): void
    {
        $santri = $perizinan->santri;
        if (! $santri) {
            return;
        }

        $wali = auth()->user()?->waliSantri ?? $santri->waliSantri->first();
        $whatsAppSettings = app(\App\Settings\WhatsAppSettings::class);

        $template = \App\Models\WhatsAppTemplate::query()
            ->where('nama', $whatsAppSettings->template_perizinan_diajukan ?? 'perizinan_diajukan')
            ->where('aktif', true)
            ->first();

        $mulai = $perizinan->tanggal_mulai?->format('d/m/Y') ?? '-';
        $selesai = $perizinan->tanggal_selesai?->format('d/m/Y') ?? '-';
        $jamRencana = $perizinan->jam_kembali_rencana ? substr($perizinan->jam_kembali_rencana, 0, 5) : '17:00';
        $durasi = ($perizinan->tanggal_mulai && $perizinan->tanggal_selesai)
            ? (int) $perizinan->tanggal_mulai->diffInDays($perizinan->tanggal_selesai) + 1
            : 1;

        $vars = [
            'nama_santri' => $santri->nama_lengkap,
            'kamar_santri' => $santri->kamar?->nama_kamar ?? 'Tanpa Kamar',
            'nama_wali' => $wali?->nama ?? 'Wali Santri',
            'no_hp_wali' => $wali?->no_hp ?? '-',
            'jenis_izin' => $perizinan->jenis_izin_label,
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'jam_kembali' => $jamRencana,
            'durasi_hari' => $durasi,
            'alasan' => $perizinan->alasan,
        ];

        $pesan = $template
            ? $template->render($vars)
            : sprintf(
                "*Pengajuan Perizinan Santri Baru*\n\nAssalamu'alaikum Warahmatullahi Wabarakatuh,\nYth. Petugas Keamanan / Pengurus,\n\nTerdapat pengajuan perizinan baru:\n• *Nama Santri*: %s (%s)\n• *Wali / Pemohon*: %s (%s)\n• *Jenis Izin*: %s\n• *Periode*: %s s.d. %s (%d hari)\n• *Rencana Kembali*: Pukul %s WIB\n• *Alasan*: %s\n\nMohon periksa dan proses persetujuan pada sistem.",
                $santri->nama_lengkap,
                $vars['kamar_santri'],
                $vars['nama_wali'],
                $vars['no_hp_wali'],
                $perizinan->jenis_izin_label,
                $mulai,
                $selesai,
                $durasi,
                $jamRencana,
                $perizinan->alasan
            );

        // Ambil daftar seluruh penerima keamanan (multi-keamanan & hotline)
        $daftarPenerima = $this->getDaftarPenerimaKeamanan();

        foreach ($daftarPenerima as $penerima) {
            $log = \App\Models\NotifikasiLog::create([
                'perizinan_id' => $perizinan->id,
                'pengurus_id' => $penerima['pengurus_id'],
                'no_hp_tujuan' => $penerima['no_hp'],
                'nama_tujuan' => $penerima['nama'],
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            \App\Jobs\KirimNotifikasiWhatsApp::dispatch($log->id);
        }

        // Kirim in-app notification ke seluruh petugas keamanan & admin
        $this->kirimDatabaseNotificationKeamanan(
            'Pengajuan Perizinan Baru',
            "Terdapat pengajuan perizinan baru dari {$santri->nama_lengkap} ({$perizinan->jenis_izin_label}).",
            'heroicon-o-paper-airplane',
            'warning'
        );
    }

    /**
     * Kirim notifikasi WhatsApp & Database ke wali santri saat perizinan disetujui.
     */
    public function kirimNotifikasiDisetujui(Perizinan $perizinan): void
    {
        $santri = $perizinan->santri;
        if (! $santri) {
            return;
        }

        $whatsAppSettings = app(\App\Settings\WhatsAppSettings::class);
        $template = \App\Models\WhatsAppTemplate::query()
            ->where('nama', $whatsAppSettings->template_perizinan_disetujui ?? 'perizinan_disetujui')
            ->where('aktif', true)
            ->first();

        $mulai = $perizinan->tanggal_mulai?->format('d/m/Y') ?? '-';
        $selesai = $perizinan->tanggal_selesai?->format('d/m/Y') ?? '-';
        $jamRencana = $perizinan->jam_kembali_rencana ? substr($perizinan->jam_kembali_rencana, 0, 5) : '17:00';
        $petugasNama = $perizinan->disetujuiOleh?->name ?? 'Petugas Keamanan';

        foreach ($santri->waliSantri as $wali) {
            $pesan = $template
                ? $template->render([
                    'nama_santri' => $santri->nama_lengkap,
                    'nama_wali' => $wali->nama,
                    'jenis_izin' => $perizinan->jenis_izin_label,
                    'tanggal_mulai' => $mulai,
                    'tanggal_selesai' => $selesai,
                    'jam_kembali' => $jamRencana,
                    'disetujui_oleh' => $petugasNama,
                ])
                : sprintf(
                    "Assalamu'alaikum Warahmatullahi Wabarakatuh,\nBapak/Ibu %s,\n\nAlhamdulillah, perizinan untuk santri:\n• *Nama Santri*: %s\n• *Jenis Izin*: %s\n• *Periode*: %s s.d. %s (Batas: %s WIB)\n\nTelah *DISETUJUI* oleh %s.\nHarap santri kembali ke pondok tepat waktu sesuai jadwal.",
                    $wali->nama,
                    $santri->nama_lengkap,
                    $perizinan->jenis_izin_label,
                    $mulai,
                    $selesai,
                    $jamRencana,
                    $petugasNama
                );

            $log = \App\Models\NotifikasiLog::create([
                'wali_santri_id' => $wali->id,
                'perizinan_id' => $perizinan->id,
                'no_hp_tujuan' => $wali->no_hp,
                'nama_tujuan' => $wali->nama,
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            \App\Jobs\KirimNotifikasiWhatsApp::dispatch($log->id);
        }

        // Kirim in-app notification ke wali santri
        $this->kirimDatabaseNotificationWali(
            $perizinan,
            'Perizinan Disetujui',
            "Pengajuan izin santri {$santri->nama_lengkap} telah DISETUJUI. Batas kembali: {$selesai} pukul {$jamRencana} WIB.",
            'heroicon-o-check-circle',
            'success'
        );
    }

    /**
     * Kirim notifikasi WhatsApp & Database ke wali santri saat perizinan ditolak.
     */
    public function kirimNotifikasiDitolak(Perizinan $perizinan): void
    {
        $santri = $perizinan->santri;
        if (! $santri) {
            return;
        }

        $whatsAppSettings = app(\App\Settings\WhatsAppSettings::class);
        $template = \App\Models\WhatsAppTemplate::query()
            ->where('nama', $whatsAppSettings->template_perizinan_ditolak ?? 'perizinan_ditolak')
            ->where('aktif', true)
            ->first();

        $mulai = $perizinan->tanggal_mulai?->format('d/m/Y') ?? '-';
        $selesai = $perizinan->tanggal_selesai?->format('d/m/Y') ?? '-';
        $alasan = $perizinan->catatan_penolakan ?: 'Tidak memenuhi ketentuan perizinan';

        foreach ($santri->waliSantri as $wali) {
            $pesan = $template
                ? $template->render([
                    'nama_santri' => $santri->nama_lengkap,
                    'nama_wali' => $wali->nama,
                    'jenis_izin' => $perizinan->jenis_izin_label,
                    'tanggal_mulai' => $mulai,
                    'tanggal_selesai' => $selesai,
                    'alasan_penolakan' => $alasan,
                ])
                : sprintf(
                    "Assalamu'alaikum Warahmatullahi Wabarakatuh,\nBapak/Ibu %s,\n\nMohon maaf, pengajuan perizinan untuk santri:\n• *Nama Santri*: %s\n• *Jenis Izin*: %s\n• *Periode*: %s s.d. %s\n\n*DITOLAK*.\n• *Alasan*: %s\n\nUntuk informasi lebih lanjut, silakan hubungi pengurus pondok pesantren.",
                    $wali->nama,
                    $santri->nama_lengkap,
                    $perizinan->jenis_izin_label,
                    $mulai,
                    $selesai,
                    $alasan
                );

            $log = \App\Models\NotifikasiLog::create([
                'wali_santri_id' => $wali->id,
                'perizinan_id' => $perizinan->id,
                'no_hp_tujuan' => $wali->no_hp,
                'nama_tujuan' => $wali->nama,
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            \App\Jobs\KirimNotifikasiWhatsApp::dispatch($log->id);
        }

        // Kirim in-app notification ke wali santri
        $this->kirimDatabaseNotificationWali(
            $perizinan,
            'Perizinan Ditolak',
            "Pengajuan izin santri {$santri->nama_lengkap} DITOLAK. Alasan: {$alasan}",
            'heroicon-o-x-circle',
            'danger'
        );
    }

    /**
     * Kirim notifikasi WhatsApp & Database ke seluruh petugas keamanan saat santri dilaporkan kembali ke pondok.
     */
    public function kirimNotifikasiKembali(Perizinan $perizinan): void
    {
        $santri = $perizinan->santri;
        if (! $santri) {
            return;
        }

        $wali = auth()->user()?->waliSantri ?? $santri->waliSantri->first();
        $whatsAppSettings = app(\App\Settings\WhatsAppSettings::class);

        $template = \App\Models\WhatsAppTemplate::query()
            ->where('nama', $whatsAppSettings->template_perizinan_kembali ?? 'perizinan_kembali')
            ->where('aktif', true)
            ->first();

        $waktuKembali = $perizinan->tanggal_kembali?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i');
        $catatan = $perizinan->catatan_kembali ?: 'Santri dilaporkan telah kembali ke pondok';
        $statusKepulangan = $perizinan->status_kepulangan_label;

        $vars = [
            'nama_santri' => $santri->nama_lengkap,
            'kamar_santri' => $santri->kamar?->nama_kamar ?? 'Tanpa Kamar',
            'nama_wali' => $wali?->nama ?? 'Wali Santri',
            'no_hp_wali' => $wali?->no_hp ?? '-',
            'tanggal_kembali' => $waktuKembali,
            'status_kepulangan' => $statusKepulangan,
            'catatan_kembali' => $catatan,
        ];

        $pesan = $template
            ? $template->render($vars)
            : sprintf(
                "*Laporan Santri Kembali ke Pondok*\n\nAssalamu'alaikum Warahmatullahi Wabarakatuh,\nYth. Petugas Keamanan / Pengurus,\n\nSantri berikut telah dilaporkan tiba / kembali ke pondok pesantren:\n• *Nama Santri*: %s (%s)\n• *Wali / Pelapor*: %s (%s)\n• *Waktu Kedatangan*: %s\n• *Status Kedatangan*: %s\n• *Catatan*: %s\n\nFoto bukti kedatangan telah diunggah ke sistem. Silakan periksa pada menu Pengajuan Perizinan.",
                $santri->nama_lengkap,
                $vars['kamar_santri'],
                $vars['nama_wali'],
                $vars['no_hp_wali'],
                $waktuKembali,
                $statusKepulangan,
                $catatan
            );

        // Ambil daftar seluruh penerima keamanan (multi-keamanan & hotline)
        $daftarPenerima = $this->getDaftarPenerimaKeamanan();

        foreach ($daftarPenerima as $penerima) {
            $log = \App\Models\NotifikasiLog::create([
                'perizinan_id' => $perizinan->id,
                'pengurus_id' => $penerima['pengurus_id'],
                'no_hp_tujuan' => $penerima['no_hp'],
                'nama_tujuan' => $penerima['nama'],
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            \App\Jobs\KirimNotifikasiWhatsApp::dispatch($log->id);
        }

        // Kirim in-app notification ke seluruh petugas keamanan & admin
        $this->kirimDatabaseNotificationKeamanan(
            'Laporan Santri Kembali',
            "Santri {$santri->nama_lengkap} dilaporkan telah kembali ke pondok ({$statusKepulangan}). Foto bukti telah diunggah.",
            'heroicon-o-check-badge',
            'info'
        );
    }

    /**
     * Kirim notifikasi pengingat batas waktu kepulangan santri ke wali santri.
     */
    public function kirimNotifikasiPengingatKembali(Perizinan $perizinan): void
    {
        $santri = $perizinan->santri;
        if (! $santri || $santri->waliSantri->isEmpty()) {
            return;
        }

        $whatsAppSettings = app(\App\Settings\WhatsAppSettings::class);
        $template = \App\Models\WhatsAppTemplate::query()
            ->where('nama', $whatsAppSettings->template_perizinan_pengingat_kembali ?? 'perizinan_pengingat_kembali')
            ->where('aktif', true)
            ->first();

        $selesai = $perizinan->tanggal_selesai?->format('d/m/Y') ?? '-';
        $jamKembali = $perizinan->jam_kembali_rencana ? substr($perizinan->jam_kembali_rencana, 0, 5) : '17:00';
        $sisaWaktu = $perizinan->sisa_waktu_label;

        foreach ($santri->waliSantri as $wali) {
            $vars = [
                'nama_santri' => $santri->nama_lengkap,
                'kamar_santri' => $santri->kamar?->nama_kamar ?? 'Tanpa Kamar',
                'nama_wali' => $wali->nama,
                'no_hp_wali' => $wali->no_hp ?? '-',
                'jenis_izin' => $perizinan->jenis_izin_label,
                'tanggal_mulai' => $perizinan->tanggal_mulai?->format('d/m/Y') ?? '-',
                'tanggal_selesai' => $selesai,
                'jam_kembali' => $jamKembali,
                'sisa_waktu' => $sisaWaktu,
            ];

            $pesan = $template
                ? $template->render($vars)
                : sprintf(
                    "*Pengingat Batas Waktu Perizinan Santri*\n\nAssalamu'alaikum Warahmatullahi Wabarakatuh,\nBapak/Ibu %s,\n\nMengingatkan kembali bahwa masa izin santri *%s* (%s) akan berakhir pada *%s pukul %s WIB* (%s).\n\nHarap ananda dipersiapkan untuk kembali ke pondok tepat waktu. Setelah tiba di pondok, mohon laporkan kepulangan dan unggah foto bukti kedatangan di portal wali santri.\n\nTerima kasih.",
                    $wali->nama,
                    $santri->nama_lengkap,
                    $vars['kamar_santri'],
                    $selesai,
                    $jamKembali,
                    $sisaWaktu
                );

            $log = \App\Models\NotifikasiLog::create([
                'wali_santri_id' => $wali->id,
                'perizinan_id' => $perizinan->id,
                'no_hp_tujuan' => $wali->no_hp,
                'nama_tujuan' => $wali->nama,
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            \App\Jobs\KirimNotifikasiWhatsApp::dispatch($log->id);
        }

        // Kirim in-app notification ke wali santri
        $this->kirimDatabaseNotificationWali(
            $perizinan,
            'Pengingat Batas Waktu Perizinan',
            "Masa perizinan {$santri->nama_lengkap} akan berakhir pada {$selesai} pukul {$jamKembali} WIB. Mohon persiapkan kepulangan tepat waktu.",
            'heroicon-o-clock',
            'warning'
        );

        $perizinan->updateQuietly([
            'pengingat_kembali_sent_at' => now(),
        ]);
    }
}
