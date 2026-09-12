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
     * Kirim notifikasi WhatsApp ke petugas keamanan / pengurus saat izin diajukan.
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
            'durasi_hari' => $durasi,
            'alasan' => $perizinan->alasan,
        ];

        $pesan = $template
            ? $template->render($vars)
            : sprintf(
                "*Pengajuan Perizinan Santri Baru*\n\nAssalamu'alaikum Warahmatullahi Wabarakatuh,\nYth. Petugas Keamanan / Pengurus,\n\nTerdapat pengajuan perizinan baru:\n• *Nama Santri*: %s (%s)\n• *Wali / Pemohon*: %s (%s)\n• *Jenis Izin*: %s\n• *Periode*: %s s.d. %s (%d hari)\n• *Alasan*: %s\n\nMohon periksa dan proses persetujuan pada sistem.",
                $santri->nama_lengkap,
                $vars['kamar_santri'],
                $vars['nama_wali'],
                $vars['no_hp_wali'],
                $perizinan->jenis_izin_label,
                $mulai,
                $selesai,
                $durasi,
                $perizinan->alasan
            );

        // Ambil daftar pengurus bagian keamanan yang memiliki no HP
        $pengurusKeamanan = \App\Models\Pengurus::query()
            ->where('bagian', 'keamanan')
            ->whereNotNull('no_hp')
            ->where('no_hp', '!=', '')
            ->get();

        $nomorTerkirim = [];

        foreach ($pengurusKeamanan as $petugas) {
            $nomorTerkirim[] = preg_replace('/\D+/', '', $petugas->no_hp);

            $log = \App\Models\NotifikasiLog::create([
                'perizinan_id' => $perizinan->id,
                'pengurus_id' => $petugas->id,
                'no_hp_tujuan' => $petugas->no_hp,
                'nama_tujuan' => $petugas->nama,
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            \App\Jobs\KirimNotifikasiWhatsApp::dispatch($log->id);
        }

        // Jika ada nomor wa keamanan tersendiri di setting dan belum terkirim
        $nomorSetting = $whatsAppSettings->nomor_wa_keamanan ? preg_replace('/\D+/', '', $whatsAppSettings->nomor_wa_keamanan) : null;
        if ($nomorSetting && ! in_array($nomorSetting, $nomorTerkirim, true)) {
            $log = \App\Models\NotifikasiLog::create([
                'perizinan_id' => $perizinan->id,
                'no_hp_tujuan' => $whatsAppSettings->nomor_wa_keamanan,
                'nama_tujuan' => 'Piket Keamanan (Pusat)',
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            \App\Jobs\KirimNotifikasiWhatsApp::dispatch($log->id);
        }
    }

    /**
     * Kirim notifikasi WhatsApp ke wali santri saat perizinan disetujui.
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
        $petugasNama = $perizinan->disetujuiOleh?->name ?? 'Petugas Keamanan';

        foreach ($santri->waliSantri as $wali) {
            $pesan = $template
                ? $template->render([
                    'nama_santri' => $santri->nama_lengkap,
                    'nama_wali' => $wali->nama,
                    'jenis_izin' => $perizinan->jenis_izin_label,
                    'tanggal_mulai' => $mulai,
                    'tanggal_selesai' => $selesai,
                    'disetujui_oleh' => $petugasNama,
                ])
                : sprintf(
                    "Assalamu'alaikum Warahmatullahi Wabarakatuh,\nBapak/Ibu %s,\n\nAlhamdulillah, perizinan untuk santri:\n• *Nama Santri*: %s\n• *Jenis Izin*: %s\n• *Periode*: %s s.d. %s\n\nTelah *DISETUJUI* oleh %s.\nHarap santri kembali ke pondok tepat waktu sesuai jadwal.",
                    $wali->nama,
                    $santri->nama_lengkap,
                    $perizinan->jenis_izin_label,
                    $mulai,
                    $selesai,
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
    }

    /**
     * Kirim notifikasi WhatsApp ke wali santri saat perizinan ditolak.
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
    }

    /**
     * Kirim notifikasi WhatsApp ke petugas keamanan saat santri dilaporkan kembali ke pondok.
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

        $vars = [
            'nama_santri' => $santri->nama_lengkap,
            'kamar_santri' => $santri->kamar?->nama_kamar ?? 'Tanpa Kamar',
            'nama_wali' => $wali?->nama ?? 'Wali Santri',
            'no_hp_wali' => $wali?->no_hp ?? '-',
            'tanggal_kembali' => $waktuKembali,
            'catatan_kembali' => $catatan,
        ];

        $pesan = $template
            ? $template->render($vars)
            : sprintf(
                "*Laporan Santri Kembali ke Pondok*\n\nAssalamu'alaikum Warahmatullahi Wabarakatuh,\nYth. Petugas Keamanan / Pengurus,\n\nSantri berikut telah dilaporkan tiba / kembali ke pondok pesantren:\n• *Nama Santri*: %s (%s)\n• *Wali / Pelapor*: %s (%s)\n• *Waktu Kedatangan*: %s\n• *Catatan*: %s\n\nFoto bukti kedatangan telah diunggah ke sistem. Silakan periksa pada menu Pengajuan Perizinan.",
                $santri->nama_lengkap,
                $vars['kamar_santri'],
                $vars['nama_wali'],
                $vars['no_hp_wali'],
                $waktuKembali,
                $catatan
            );

        // Ambil daftar pengurus bagian keamanan yang memiliki no HP
        $pengurusKeamanan = \App\Models\Pengurus::query()
            ->where('bagian', 'keamanan')
            ->whereNotNull('no_hp')
            ->where('no_hp', '!=', '')
            ->get();

        $nomorTerkirim = [];

        foreach ($pengurusKeamanan as $petugas) {
            $nomorTerkirim[] = preg_replace('/\D+/', '', $petugas->no_hp);

            $log = \App\Models\NotifikasiLog::create([
                'perizinan_id' => $perizinan->id,
                'pengurus_id' => $petugas->id,
                'no_hp_tujuan' => $petugas->no_hp,
                'nama_tujuan' => $petugas->nama,
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            \App\Jobs\KirimNotifikasiWhatsApp::dispatch($log->id);
        }

        // Jika ada nomor wa keamanan tersendiri di setting dan belum terkirim
        $nomorSetting = $whatsAppSettings->nomor_wa_keamanan ? preg_replace('/\D+/', '', $whatsAppSettings->nomor_wa_keamanan) : null;
        if ($nomorSetting && ! in_array($nomorSetting, $nomorTerkirim, true)) {
            $log = \App\Models\NotifikasiLog::create([
                'perizinan_id' => $perizinan->id,
                'no_hp_tujuan' => $whatsAppSettings->nomor_wa_keamanan,
                'nama_tujuan' => 'Piket Keamanan (Pusat)',
                'channel' => 'whatsapp',
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            \App\Jobs\KirimNotifikasiWhatsApp::dispatch($log->id);
        }
    }
}
