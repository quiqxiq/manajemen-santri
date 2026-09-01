<?php

namespace Database\Seeders;

use App\Models\KategoriPelanggaran;
use App\Models\NotifikasiLog;
use App\Models\Pelanggaran;
use App\Models\Pembayaran;
use App\Models\Penghargaan;
use App\Models\Perizinan;
use App\Models\Santri;
use App\Models\Tagihan;
use App\Models\Tahfidz;
use App\Models\User;
use App\Models\WaliSantri;
use App\Models\WhatsAppTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder data mock untuk mengisi semua menu aplikasi (idempotent).
 *
 * - Hanya menambah data ke tabel yang jumlahnya masih kurang dari target;
 *   data asli (santri dari Excel, wali, user, dll.) TIDAK diubah/dihapus.
 * - Aman dijalankan berulang kali (php artisan db:seed --class=MockDataSeeder).
 */
class MockDataSeeder extends Seeder
{
    public function run(): void
    {
        $santri = Santri::orderBy('id')->get();
        if ($santri->isEmpty()) {
            $this->command?->warn('Tidak ada data santri — jalankan seeder data asli terlebih dahulu.');

            return;
        }

        $pengurusIds = \App\Models\Pengurus::orderBy('id')->pluck('id')->all();
        $kategori = KategoriPelanggaran::orderBy('id')->get();
        $adminId = User::orderBy('id')->value('id');

        $pick = fn (array $items) => $items[array_rand($items)];

        // ---------------------------------------------------------------- //
        // Pelanggaran (target 8)
        // ---------------------------------------------------------------- //
        $this->fillUpTo(Pelanggaran::class, 8, function () use ($santri, $kategori, $pengurusIds, $pick) {
            $s = $santri->random();
            $kat = $kategori->random();

            return [
                'santri_id' => $s->id,
                'kategori_pelanggaran_id' => $kat->id,
                'pengurus_id' => $pick($pengurusIds),
                'deskripsi' => 'Pelanggaran mock: '.$kat->nama_kategori,
                'tanggal_kejadian' => now()->subDays(rand(1, 30))->toDateString(),
                'poin' => $kat->poin,
                'status' => 'normal',
            ];
        });

        // ---------------------------------------------------------------- //
        // Penghargaan (target 6)
        // ---------------------------------------------------------------- //
        $this->fillUpTo(Penghargaan::class, 6, function () use ($santri, $pengurusIds, $pick) {
            $bidang = ['akademik', 'tahfidz', 'kedisiplinan', 'lomba'];

            return [
                'santri_id' => $santri->random()->id,
                'pengurus_id' => $pick($pengurusIds),
                'bidang' => $pick($bidang),
                'deskripsi' => 'Penghargaan mock atas prestasi santri',
                'tanggal' => now()->subDays(rand(1, 60))->toDateString(),
            ];
        });

        // ---------------------------------------------------------------- //
        // Perizinan (target 6)
        // ---------------------------------------------------------------- //
        $this->fillUpTo(Perizinan::class, 6, function () use ($santri, $pick) {
            $jenis = ['pulang', 'sakit', 'acara_keluarga', 'lainnya'];
            $status = ['diajukan', 'disetujui', 'selesai'];

            return [
                'santri_id' => $santri->random()->id,
                'jenis_izin' => $pick($jenis),
                'tanggal_mulai' => now()->addDays(rand(0, 7))->toDateString(),
                'tanggal_selesai' => now()->addDays(rand(2, 10))->toDateString(),
                'alasan' => 'Izin mock: keperluan keluarga',
                'status' => $pick($status),
            ];
        });

        // ---------------------------------------------------------------- //
        // Tahfidz (target 8)
        // ---------------------------------------------------------------- //
        $this->fillUpTo(Tahfidz::class, 8, function () use ($santri, $pengurusIds, $pick) {
            $surat = ['An-Naba', 'An-Nazi\'at', 'Abasa', 'At-Takwir', 'Al-Infitar', 'Al-Mutaffifin', 'Al-Insyiqaq', 'Al-Buruj'];
            $jenis = ['setoran', 'murojaah'];
            $status = ['lulus', 'tidak_lulus'];

            return [
                'santri_id' => $santri->random()->id,
                'pengurus_id' => $pick($pengurusIds),
                'jenis' => $pick($jenis),
                'surat' => $pick($surat),
                'juz' => 30,
                'ayat_dari' => rand(1, 20),
                'ayat_sampai' => rand(21, 42),
                'status' => $pick($status),
                'catatan' => 'Setoran mock — lancar',
                'tanggal' => now()->subDays(rand(0, 20))->toDateString(),
            ];
        });

        // ---------------------------------------------------------------- //
        // Tagihan (target 10)
        // ---------------------------------------------------------------- //
        $this->fillUpTo(Tagihan::class, 10, function () use ($santri, $pick) {
            $jenis = ['spp', 'daftar_ulang', 'lainnya'];
            $status = ['belum_lunas', 'sebagian', 'lunas'];
            $nominal = [250000, 300000, 350000, 500000];

            return [
                'santri_id' => $santri->random()->id,
                'jenis' => $pick($jenis),
                'bulan' => rand(1, 12),
                'tahun' => 2026,
                'nominal' => $pick($nominal),
                'status' => $pick($status),
                'jatuh_tempo' => now()->addDays(rand(-15, 30))->toDateString(),
            ];
        });

        // ---------------------------------------------------------------- //
        // Pembayaran (target 6)
        // ---------------------------------------------------------------- //
        $this->fillUpTo(Pembayaran::class, 6, function () use ($santri, $adminId, $pick) {
            $tagihan = Tagihan::where('status', '!=', 'lunas')->first() ?? Tagihan::first();
            if (! $tagihan) {
                return null;
            }
            $metode = ['tunai', 'transfer', 'qris'];

            return [
                'tagihan_id' => $tagihan->id,
                'santri_id' => $tagihan->santri_id ?: $santri->random()->id,
                'jumlah_bayar' => $tagihan->nominal,
                'tanggal_bayar' => now()->subDays(rand(0, 20))->toDateString(),
                'metode_pembayaran' => $pick($metode),
                'admin_id' => $adminId,
            ];
        });

        // ---------------------------------------------------------------- //
        // Notifikasi Log (target 6)
        // ---------------------------------------------------------------- //
        $this->fillUpTo(NotifikasiLog::class, 6, function () use ($pick) {
            $wali = WaliSantri::inRandomOrder()->first();
            $pelanggaran = Pelanggaran::inRandomOrder()->first();
            if (! $wali) {
                return null;
            }
            $status = ['pending', 'sent', 'failed'];

            return [
                'wali_santri_id' => $wali->id,
                'pelanggaran_id' => $pelanggaran?->id,
                'channel' => 'whatsapp',
                'pesan' => 'Pemberitahuan notifikasi mock kepada wali santri.',
                'status' => $pick($status),
                'attempts' => rand(0, 2),
                'sent_at' => now()->subHours(rand(1, 48)),
            ];
        });

        // ---------------------------------------------------------------- //
        // WhatsApp Template (tambah yang belum ada)
        // ---------------------------------------------------------------- //
        $templates = [
            [
                'nama' => 'tagihan_berjalan',
                'judul' => 'Pemberitahuan Tagihan (R1)',
                'pesan' => "Assalamu'alaikum Bapak/Ibu {nama_wali},\n\nAnanda {nama_santri} memiliki tagihan {jenis} sebesar Rp{nominal} yang harus dilunasi sebelum {jatuh_tempo}.\n\nHormat kami,\nPondok Pesantren",
            ],
            [
                'nama' => 'perizinan_status',
                'judul' => 'Status Perizinan Santri',
                'pesan' => "Assalamu'alaikum Bapak/Ibu {nama_wali},\n\nPerizinan {jenis_izin} untuk ananda {nama_santri} berstatus {status}.\n\nHormat kami,\nPondok Pesantren",
            ],
            [
                'nama' => 'pengumuman_umum',
                'judul' => 'Pengumuman Umum',
                'pesan' => "Assalamu'alaikum Bapak/Ibu {nama_wali},\n\n{pengumuman}\n\nHormat kami,\nPondok Pesantren",
            ],
        ];

        foreach ($templates as $t) {
            WhatsAppTemplate::firstOrCreate(['nama' => $t['nama']], [
                'judul' => $t['judul'],
                'pesan' => $t['pesan'],
                'aktif' => true,
            ]);
        }
    }

    /**
     * Tambahkan record mock hingga jumlah record tabel mencapai $target.
     * Data yang sudah ada tidak pernah diubah/dihapus.
     */
    private function fillUpTo(string $model, int $target, callable $factory): void
    {
        $existing = $model::count();

        if ($existing >= $target) {
            return;
        }

        $batch = [];
        for ($i = 0; $i < ($target - $existing); $i++) {
            $attrs = $factory();
            if ($attrs === null) {
                continue;
            }
            $batch[] = $attrs;
        }

        if (! empty($batch)) {
            $model::insert($batch);
        }
    }
}
