<?php

namespace Database\Seeders;

use App\Models\Kamar;
use App\Models\KategoriPelanggaran;
use App\Models\Pelanggaran;
use App\Models\Pembayaran;
use App\Models\Pengurus;
use App\Models\Perizinan;
use App\Models\Santri;
use App\Models\Tagihan;
use App\Models\Tahfidz;
use App\Models\User;
use App\Models\WaliSantri;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
        ]);

        // Create Master Data
        $kamarA = Kamar::create(['nama_kamar' => 'Kamar Al-Ghazali', 'kapasitas' => 20, 'keterangan' => 'Gedung A Lantai 1']);
        $kamarB = Kamar::create(['nama_kamar' => 'Kamar Ibnu Sina', 'kapasitas' => 20, 'keterangan' => 'Gedung A Lantai 2']);
        $kamarC = Kamar::create(['nama_kamar' => 'Kamar Al-Farabi', 'kapasitas' => 15, 'keterangan' => 'Gedung B Lantai 1']);

        $katRingan = KategoriPelanggaran::create(['nama_kategori' => 'Terlambat Berjamaah', 'poin' => 5]);
        $katSedang = KategoriPelanggaran::create(['nama_kategori' => 'Keluar Tanpa Izin', 'poin' => 25]);
        $katBerat = KategoriPelanggaran::create(['nama_kategori' => 'Membawa HP / Elektronik Terlarang', 'poin' => 50]);

        // Link Pengurus
        $keamananUser = User::where('username', 'keamanan')->first();
        $ustadzUser = User::where('username', 'ustadz')->first();
        $pengasuhUser = User::where('username', 'pengasuh')->first();

        $pengurusKeamanan = Pengurus::create(['user_id' => $keamananUser->id, 'nama' => 'Pengurus Keamanan', 'bagian' => 'keamanan', 'no_hp' => '081234567890']);
        $pengurusUstadz = Pengurus::create(['user_id' => $ustadzUser->id, 'nama' => 'Ustadz Ahmad', 'bagian' => 'tahfidz', 'no_hp' => '081234567891']);
        $pengurusPengasuh = Pengurus::create(['user_id' => $pengasuhUser->id, 'nama' => 'KH. Abdullah', 'bagian' => 'pengasuhan', 'no_hp' => '081234567892']);

        $kamarA->update(['pengurus_pembina_id' => $pengurusKeamanan->id]);
        $kamarB->update(['pengurus_pembina_id' => $pengurusUstadz->id]);

        // Link Demo Wali & Santri
        $waliUser = User::where('username', 'wali')->first();
        $santriUser = User::where('username', 'santri')->first(); // portal santri dihapus, user_id dibiarkan null

        $waliDemo = WaliSantri::create([
            'user_id' => $waliUser->id,
            'nama' => 'H. Ahmad Subandi (Wali Demo)',
            'no_hp' => '089876543210',
            'pekerjaan' => 'Wiraswasta',
        ]);

        $santriDemo = Santri::create([
            'user_id' => $santriUser?->id,
            'nis' => 'SAN-2026001',
            'nama_lengkap' => 'Muhammad Ali (Santri Demo)',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2010-05-15',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Pesantren No. 123',
            'asal_sekolah' => 'SMPN 1 Bandung',
            'kamar_id' => $kamarA->id,
            'status' => 'aktif',
            'tanggal_masuk' => '2024-07-10',
        ]);

        $santriDemo->waliSantri()->attach($waliDemo->id, [
            'hubungan' => 'ayah',
            'is_penanggung_jawab_utama' => true,
        ]);

        // Additional sample Santri
        $santri2 = Santri::create([
            'nis' => 'SAN-2026002',
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2011-08-20',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Merdeka No. 45',
            'asal_sekolah' => 'MTSN 2 Jakarta',
            'kamar_id' => $kamarB->id,
            'status' => 'aktif',
            'tanggal_masuk' => '2025-07-10',
        ]);

        $santri2->waliSantri()->attach($waliDemo->id, [
            'hubungan' => 'ayah',
            'is_penanggung_jawab_utama' => false,
        ]);

        // Tahfidz Records
        Tahfidz::create([
            'santri_id' => $santriDemo->id,
            'pengurus_id' => $pengurusUstadz->id,
            'jenis' => 'setoran',
            'surat' => 'An-Naba',
            'juz' => 30,
            'ayat_dari' => 1,
            'ayat_sampai' => 40,
            'status' => 'lulus',
            'catatan' => 'Lancar dan fasih',
            'tanggal' => now(),
        ]);

        // Tagihan & Pembayaran for Santri Demo (Lunas)
        $tagihan1 = Tagihan::create([
            'santri_id' => $santriDemo->id,
            'jenis' => 'spp',
            'bulan' => 7,
            'tahun' => 2026,
            'nominal' => 500000,
            'status' => 'lunas',
            'jatuh_tempo' => '2026-07-10',
        ]);

        Pembayaran::create([
            'tagihan_id' => $tagihan1->id,
            'santri_id' => $santriDemo->id,
            'jumlah_bayar' => 500000,
            'tanggal_bayar' => '2026-07-05',
            'metode_pembayaran' => 'transfer',
            'admin_id' => User::where('username', 'keuangan')->first()->id,
        ]);

        // Tagihan Belum Lunas for Santri 2 (to test Rule R1 rejection)
        Tagihan::create([
            'santri_id' => $santri2->id,
            'jenis' => 'spp',
            'bulan' => 7,
            'tahun' => 2026,
            'nominal' => 500000,
            'status' => 'belum_lunas',
            'jatuh_tempo' => '2026-07-10',
        ]);

        // Sample Perizinan
        Perizinan::create([
            'santri_id' => $santriDemo->id,
            'jenis_izin' => 'acara_keluarga',
            'tanggal_mulai' => now()->addDays(1),
            'tanggal_selesai' => now()->addDays(3),
            'alasan' => 'Menghadiri pernikahan kakak',
            'status' => 'diajukan',
        ]);

        // Data santri asli dari Excel (56 santri + 5 kamar)
        $this->call(SantriMiftahulIhsanSeeder::class);

        // Akun wali santri (orang tua) dari Excel — username = digit no HP, password: password
        $this->call(WaliSantriMiftahulIhsanSeeder::class);

        // Data mock untuk semua menu (idempotent — tidak mengubah data asli)
        $this->call(MockDataSeeder::class);
    }
}
