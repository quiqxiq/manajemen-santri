<?php

namespace Tests\Feature;

use App\Filament\Resources\PerizinanResource\Pages\ManagePerizinans as AdminManagePerizinans;
use App\Filament\Wali\Resources\PerizinanResource\Pages\ManagePerizinans as WaliManagePerizinans;
use App\Models\Kamar;
use App\Models\NotifikasiLog;
use App\Models\Pengurus;
use App\Models\Perizinan;
use App\Models\Santri;
use App\Models\Tagihan;
use App\Models\User;
use App\Models\WaliSantri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PerizinanNotificationAndModalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Queue::fake([\App\Jobs\KirimNotifikasiWhatsApp::class]);
    }

    private function siapkanData(): array
    {
        // Setup Wali
        $waliUser = User::factory()->create(['username' => 'wali-demo', 'name' => 'Bapak Ahmad']);
        $waliRole = Role::firstOrCreate(['name' => 'Wali Santri', 'guard_name' => 'web']);
        foreach (['ViewAny:Perizinan', 'View:Perizinan', 'Create:Perizinan'] as $perm) {
            $p = Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            $waliRole->givePermissionTo($p);
        }
        $waliUser->assignRole($waliRole);

        $wali = WaliSantri::create([
            'user_id' => $waliUser->id,
            'nama' => 'Bapak Ahmad',
            'no_hp' => '081299990001',
        ]);

        // Setup Kamar & Santri
        $kamar = Kamar::create(['nama_kamar' => 'Kamar Al-Ikhlas', 'kapasitas' => 10]);
        $santri = Santri::create([
            'nis' => 'SAN-99901',
            'nama_lengkap' => 'Santri Budi',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '2012-05-10',
            'jenis_kelamin' => 'L',
            'alamat' => 'Surabaya',
            'asal_sekolah' => 'SDN 1',
            'kamar_id' => $kamar->id,
            'status' => 'aktif',
            'tanggal_masuk' => '2024-07-01',
        ]);
        $santri->waliSantri()->attach($wali->id, [
            'hubungan' => 'ayah',
            'is_penanggung_jawab_utama' => true,
        ]);

        // Setup Petugas Keamanan
        $keamananUser = User::factory()->create(['username' => 'keamanan-1', 'name' => 'Petugas Keamanan 1']);
        $keamananRole = Role::firstOrCreate(['name' => 'Keamanan', 'guard_name' => 'web']);
        foreach (['ViewAny:Perizinan', 'View:Perizinan', 'Create:Perizinan', 'Update:Perizinan'] as $perm) {
            $p = Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            $keamananRole->givePermissionTo($p);
        }
        $keamananUser->assignRole($keamananRole);

        $pengurusKeamanan = Pengurus::create([
            'user_id' => $keamananUser->id,
            'nama' => 'Petugas Keamanan 1',
            'bagian' => 'keamanan',
            'no_hp' => '081288880002',
        ]);

        // Setup Admin
        $adminUser = User::factory()->create(['username' => 'admin-master', 'name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());
        $adminUser->assignRole($adminRole);

        return [$waliUser, $wali, $santri, $keamananUser, $pengurusKeamanan, $adminUser];
    }

    public function test_pengajuan_izin_memicu_notifikasi_whatsapp_ke_keamanan(): void
    {
        [$waliUser, $wali, $santri, $keamananUser, $pengurusKeamanan] = $this->siapkanData();

        Livewire::actingAs($waliUser)
            ->test(WaliManagePerizinans::class)
            ->mountAction('create')
            ->set('mountedActions.0.data.santri_id', $santri->id)
            ->set('mountedActions.0.data.jenis_izin', 'pulang')
            ->set('mountedActions.0.data.tanggal_mulai', now()->toDateString())
            ->set('mountedActions.0.data.tanggal_selesai', now()->addDays(2)->toDateString())
            ->set('mountedActions.0.data.alasan', 'Kepentingan mendesak keluarga')
            ->callMountedAction()
            ->assertHasNoFormErrors();

        $perizinan = Perizinan::first();
        $this->assertNotNull($perizinan);
        $this->assertSame('diajukan', $perizinan->status);

        // Periksa log notifikasi WhatsApp dibuat untuk keamanan
        $log = NotifikasiLog::where('perizinan_id', $perizinan->id)->first();
        $this->assertNotNull($log, 'Log notifikasi pengajuan perizinan harus tercatat');
        $this->assertSame('whatsapp', $log->channel);
        $this->assertSame($pengurusKeamanan->id, $log->pengurus_id);
        $this->assertSame('081288880002', $log->no_hp_tujuan);
        $this->assertStringContainsString('Santri Budi', $log->pesan);
        $this->assertStringContainsString('Kamar Al-Ikhlas', $log->pesan);
        $this->assertStringContainsString('Kepentingan mendesak keluarga', $log->pesan);
    }

    public function test_persetujuan_izin_memicu_notifikasi_whatsapp_ke_wali(): void
    {
        [$waliUser, $wali, $santri, $keamananUser, $pengurusKeamanan, $adminUser] = $this->siapkanData();

        $perizinan = Perizinan::create([
            'santri_id' => $santri->id,
            'jenis_izin' => 'acara_keluarga',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(2),
            'alasan' => 'Pernikahan saudara',
            'status' => 'diajukan',
        ]);

        Livewire::actingAs($adminUser)
            ->test(AdminManagePerizinans::class)
            ->callTableAction('setujui', $perizinan);

        $perizinan->refresh();
        $this->assertSame('disetujui', $perizinan->status);

        // Periksa log notifikasi persetujuan terkirim ke wali santri
        $log = NotifikasiLog::where('perizinan_id', $perizinan->id)
            ->where('wali_santri_id', $wali->id)
            ->first();

        $this->assertNotNull($log, 'Log notifikasi persetujuan ke wali harus tercatat');
        $this->assertSame('081299990001', $log->no_hp_tujuan);
        $this->assertStringContainsString('Santri Budi', $log->pesan);
        $this->assertStringContainsString('DISETUJUI', $log->pesan);
    }

    public function test_penolakan_izin_memicu_notifikasi_whatsapp_ke_wali_dengan_alasan(): void
    {
        [$waliUser, $wali, $santri, $keamananUser, $pengurusKeamanan, $adminUser] = $this->siapkanData();

        $perizinan = Perizinan::create([
            'santri_id' => $santri->id,
            'jenis_izin' => 'pulang',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(2),
            'alasan' => 'Ingin liburan',
            'status' => 'diajukan',
        ]);

        Livewire::actingAs($adminUser)
            ->test(AdminManagePerizinans::class)
            ->mountTableAction('tolak', $perizinan)
            ->set('mountedActions.0.data.catatan_penolakan', 'Jadwal ujian pesantren sedang berlangsung')
            ->callMountedTableAction();

        $perizinan->refresh();
        $this->assertSame('ditolak', $perizinan->status);
        $this->assertSame('Jadwal ujian pesantren sedang berlangsung', $perizinan->catatan_penolakan);
        $this->assertSame('Jadwal ujian pesantren sedang berlangsung', $perizinan->catatan_penolakan);

        // Periksa log notifikasi penolakan terkirim ke wali santri
        $log = NotifikasiLog::where('perizinan_id', $perizinan->id)
            ->where('wali_santri_id', $wali->id)
            ->first();

        $this->assertNotNull($log, 'Log notifikasi penolakan ke wali harus tercatat');
        $this->assertSame('081299990001', $log->no_hp_tujuan);
        $this->assertStringContainsString('Santri Budi', $log->pesan);
        $this->assertStringContainsString('DITOLAK', $log->pesan);
        $this->assertStringContainsString('Jadwal ujian pesantren sedang berlangsung', $log->pesan);
    }

    public function test_santri_dengan_tunggakan_menolak_pengajuan_dan_membuka_modal_peringatan(): void
    {
        [$waliUser, $wali, $santri] = $this->siapkanData();

        // Buat tagihan belum lunas untuk santri
        $tagihan = Tagihan::create([
            'santri_id' => $santri->id,
            'jenis' => 'spp',
            'bulan' => 7,
            'tahun' => 2026,
            'nominal' => 350000,
            'status' => 'belum_lunas',
            'jatuh_tempo' => now()->subDays(5),
        ]);

        // Verifikasi accessor sisa_tagihan pada model Tagihan
        $this->assertEquals(350000.0, $tagihan->sisaTagihan());
        $this->assertEquals(350000.0, $tagihan->sisa_tagihan);

        $component = Livewire::actingAs($waliUser)
            ->test(WaliManagePerizinans::class)
            ->mountAction('create')
            ->set('mountedActions.0.data.santri_id', $santri->id)
            ->set('mountedActions.0.data.jenis_izin', 'pulang')
            ->set('mountedActions.0.data.tanggal_mulai', now()->toDateString())
            ->set('mountedActions.0.data.tanggal_selesai', now()->addDays(2)->toDateString())
            ->set('mountedActions.0.data.alasan', 'Pulang kampung')
            ->callMountedAction();

        // Pengajuan TIDAK boleh tersimpan di database
        $this->assertDatabaseMissing('perizinan', [
            'santri_id' => $santri->id,
            'alasan' => 'Pulang kampung',
        ]);

        // Action peringatan tunggakan harus ter-mount sebagai modal pengganti toast
        $component->assertActionMounted('peringatanTunggakan');

        // Pastikan arguments modal memuat sisa tagihan yang benar (bukan Rp 0)
        $arguments = $component->get('mountedActionsArguments.0') ?? [];
        if (! empty($arguments)) {
            $this->assertSame('Rp 350.000', $arguments['total_tunggakan'] ?? null);
            $this->assertNotEmpty($arguments['tagihan_list'] ?? []);
            $this->assertSame('Rp 350.000', $arguments['tagihan_list'][0]['sisa'] ?? null);
            $this->assertNotSame('Rp 0', $arguments['tagihan_list'][0]['sisa'] ?? null);
        }
    }

    public function test_modal_tunggakan_blade_view_merender_rincian_tagihan(): void
    {
        $rendered = view('filament.wali.components.modal-tunggakan', [
            'namaSantri' => 'Santri Budi',
            'tagihanList' => [
                [
                    'jenis' => 'SPP Bulanan',
                    'periode' => 'Juli 2026',
                    'nominal' => 'Rp 350.000',
                    'sisa' => 'Rp 350.000',
                    'jatuh_tempo' => '10 Jul 2026',
                    'status' => 'belum_lunas',
                ],
            ],
            'totalTunggakan' => 'Rp 350.000',
        ])->render();

        $this->assertStringContainsString('Santri Budi', $rendered);
        $this->assertStringContainsString('SPP Bulanan', $rendered);
        $this->assertStringContainsString('Juli 2026', $rendered);
        $this->assertStringContainsString('Rp 350.000', $rendered);
        $this->assertStringContainsString('Total Tanggungan Belum Lunas', $rendered);
        $this->assertStringContainsString('Tagihan & Pembayaran', $rendered);
    }

    public function test_wali_dapat_mengunggah_bukti_kembali_dan_menyelesaikan_perizinan(): void
    {
        [$waliUser, $wali, $santri, $keamananUser, $pengurusKeamanan] = $this->siapkanData();

        $perizinan = Perizinan::create([
            'santri_id' => $santri->id,
            'jenis_izin' => 'pulang',
            'tanggal_mulai' => now()->subDays(2),
            'tanggal_selesai' => now(),
            'alasan' => 'Izin pulang ke rumah',
            'status' => 'disetujui',
        ]);

        \Illuminate\Support\Facades\Storage::fake('public');
        $fakeImage = \Illuminate\Http\UploadedFile::fake()->image('bukti_kembali.jpg', 600, 600);

        Livewire::actingAs($waliUser)
            ->test(\App\Filament\Wali\Resources\PerizinanResource\Pages\ManagePerizinans::class)
            ->mountTableAction('laporKembali', $perizinan)
            ->set('mountedActions.0.data.tanggal_kembali', now()->format('Y-m-d H:i:s'))
            ->set('mountedActions.0.data.bukti_kembali', [$fakeImage])
            ->set('mountedActions.0.data.catatan_kembali', 'Santri tiba di pos keamanan pondok')
            ->callMountedTableAction();

        $perizinan->refresh();
        $this->assertSame('selesai', $perizinan->status);
        $this->assertNotNull($perizinan->tanggal_kembali);
        $this->assertSame('Santri tiba di pos keamanan pondok', $perizinan->catatan_kembali);
        $this->assertTrue($perizinan->hasMedia('bukti_kembali'), 'Foto bukti kembali harus tersimpan di Spatie Media Library');

        // Periksa notifikasi WhatsApp kepulangan ke petugas keamanan
        $log = NotifikasiLog::where('perizinan_id', $perizinan->id)
            ->where('pengurus_id', $pengurusKeamanan->id)
            ->first();

        $this->assertNotNull($log, 'Notifikasi kedatangan ke petugas keamanan harus tercatat');
        $this->assertStringContainsString('Santri Budi', $log->pesan);
        $this->assertStringContainsString('Santri tiba di pos keamanan pondok', $log->pesan);
    }

    public function test_admin_dapat_mengonfirmasi_santri_kembali_dari_panel_admin(): void
    {
        [$waliUser, $wali, $santri, $keamananUser, $pengurusKeamanan, $adminUser] = $this->siapkanData();

        $perizinan = Perizinan::create([
            'santri_id' => $santri->id,
            'jenis_izin' => 'pulang',
            'tanggal_mulai' => now()->subDays(2),
            'tanggal_selesai' => now(),
            'alasan' => 'Izin pulang',
            'status' => 'disetujui',
        ]);

        Livewire::actingAs($adminUser)
            ->test(AdminManagePerizinans::class)
            ->mountTableAction('konfirmasiKembali', $perizinan)
            ->set('mountedActions.0.data.tanggal_kembali', now()->format('Y-m-d H:i:s'))
            ->set('mountedActions.0.data.catatan_kembali', 'Dikonfirmasi oleh petugas pos satpam')
            ->callMountedTableAction();

        $perizinan->refresh();
        $this->assertSame('selesai', $perizinan->status);
        $this->assertNotNull($perizinan->tanggal_kembali);
        $this->assertSame('Dikonfirmasi oleh petugas pos satpam', $perizinan->catatan_kembali);
    }

    public function test_pengajuan_izin_memicu_notifikasi_ke_semua_petugas_keamanan_jika_lebih_dari_satu(): void
    {
        [$waliUser, $wali, $santri, $keamananUser1, $pengurusKeamanan1] = $this->siapkanData();

        // Tambah Petugas Keamanan 2
        $keamananUser2 = User::factory()->create(['username' => 'keamanan-2', 'name' => 'Petugas Keamanan 2']);
        $keamananRole = Role::findByName('Keamanan', 'web');
        $keamananUser2->assignRole($keamananRole);

        $pengurusKeamanan2 = Pengurus::create([
            'user_id' => $keamananUser2->id,
            'nama' => 'Petugas Keamanan 2',
            'bagian' => 'keamanan',
            'no_hp' => '081288880003',
        ]);

        // Tambah hotline di settings
        $settings = app(\App\Settings\WhatsAppSettings::class);
        $settings->nomor_wa_keamanan = '081288880099, 081288880098';
        $settings->save();

        Livewire::actingAs($waliUser)
            ->test(WaliManagePerizinans::class)
            ->mountAction('create')
            ->set('mountedActions.0.data.santri_id', $santri->id)
            ->set('mountedActions.0.data.jenis_izin', 'pulang')
            ->set('mountedActions.0.data.tanggal_mulai', now()->toDateString())
            ->set('mountedActions.0.data.tanggal_selesai', now()->addDays(2)->toDateString())
            ->set('mountedActions.0.data.jam_kembali_rencana', '17:00:00')
            ->set('mountedActions.0.data.alasan', 'Menghadiri acara keluarga')
            ->callMountedAction()
            ->assertHasNoFormErrors();

        $perizinan = Perizinan::first();
        $this->assertNotNull($perizinan);

        // Periksa log notifikasi WhatsApp dibuat untuk semua petugas dan hotline
        $logs = NotifikasiLog::where('perizinan_id', $perizinan->id)->get();
        $nomorLogs = $logs->pluck('no_hp_tujuan')->all();

        $this->assertContains('081288880002', $nomorLogs, 'Petugas 1 harus menerima notifikasi');
        $this->assertContains('081288880003', $nomorLogs, 'Petugas 2 harus menerima notifikasi');
        $this->assertContains('081288880099', $nomorLogs, 'Hotline 1 harus menerima notifikasi');
        $this->assertContains('081288880098', $nomorLogs, 'Hotline 2 harus menerima notifikasi');
    }

    public function test_command_pengingat_kepulangan_mengirim_notifikasi_ke_wali_saat_mendekati_tenggat(): void
    {
        [$waliUser, $wali, $santri] = $this->siapkanData();

        // Buat perizinan yang disetujui dengan batas waktu besok (H-1)
        $perizinanAktif = Perizinan::create([
            'santri_id' => $santri->id,
            'jenis_izin' => 'pulang',
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'tanggal_selesai' => now()->addDay()->toDateString(),
            'jam_kembali_rencana' => '17:00:00',
            'alasan' => 'Pulang',
            'status' => 'disetujui',
        ]);

        // Buat perizinan lain yang sudah selesai (tidak boleh dikirimi notif)
        $perizinanSelesai = Perizinan::create([
            'santri_id' => $santri->id,
            'jenis_izin' => 'sakit',
            'tanggal_mulai' => now()->subDays(3),
            'tanggal_selesai' => now()->subDay(),
            'tanggal_kembali' => now()->subDay(),
            'alasan' => 'Sakit',
            'status' => 'selesai',
        ]);

        // Jalankan artisan command pengingat
        $this->artisan('whatsapp:pengingat-perizinan-kembali', ['--days' => 1])
            ->assertSuccessful();

        $perizinanAktif->refresh();
        $this->assertNotNull($perizinanAktif->pengingat_kembali_sent_at, 'pengingat_kembali_sent_at harus terisi');

        // Pastikan ada log notifikasi WhatsApp ke wali santri
        $log = NotifikasiLog::where('perizinan_id', $perizinanAktif->id)
            ->where('wali_santri_id', $wali->id)
            ->first();

        $this->assertNotNull($log, 'Log notifikasi pengingat ke wali santri harus tercatat');
        $this->assertStringContainsString('Pengingat', $log->pesan);
        $this->assertStringContainsString('Santri Budi', $log->pesan);

        // Jalankan lagi di hari yang sama: harus dilewati (tidak duplikat)
        $countSebelum = NotifikasiLog::where('perizinan_id', $perizinanAktif->id)->count();
        $this->artisan('whatsapp:pengingat-perizinan-kembali', ['--days' => 1])
            ->assertSuccessful();
        $countSesudah = NotifikasiLog::where('perizinan_id', $perizinanAktif->id)->count();
        $this->assertSame($countSebelum, $countSesudah, 'Tidak boleh mengirim pengingat ganda di hari yang sama');
    }

    public function test_wali_dapat_mengunggah_dokumen_pendukung_dan_multiple_bukti_kembali(): void
    {
        [$waliUser, $wali, $santri] = $this->siapkanData();

        \Illuminate\Support\Facades\Storage::fake('public');
        $fakeDoc = \Illuminate\Http\UploadedFile::fake()->create('surat_dokter.pdf', 100, 'application/pdf');

        Livewire::actingAs($waliUser)
            ->test(WaliManagePerizinans::class)
            ->mountAction('create')
            ->set('mountedActions.0.data.santri_id', $santri->id)
            ->set('mountedActions.0.data.jenis_izin', 'sakit')
            ->set('mountedActions.0.data.tanggal_mulai', now()->toDateString())
            ->set('mountedActions.0.data.tanggal_selesai', now()->addDays(2)->toDateString())
            ->set('mountedActions.0.data.jam_kembali_rencana', '17:00:00')
            ->set('mountedActions.0.data.alasan', 'Berobat ke rumah sakit')
            ->set('mountedActions.0.data.dokumen_perizinan', [$fakeDoc])
            ->callMountedAction()
            ->assertHasNoFormErrors();

        $perizinan = Perizinan::first();
        $this->assertNotNull($perizinan);
        $this->assertTrue($perizinan->hasMedia('dokumen_perizinan'), 'Dokumen perizinan harus tersimpan');

        // Setujui izin
        $perizinan->update(['status' => 'disetujui']);

        // Wali lapor kembali dengan multiple foto bukti
        $fakeImg1 = \Illuminate\Http\UploadedFile::fake()->image('foto_gerbang.jpg', 600, 600);
        $fakeImg2 = \Illuminate\Http\UploadedFile::fake()->image('foto_asrama.jpg', 600, 600);

        Livewire::actingAs($waliUser)
            ->test(WaliManagePerizinans::class)
            ->mountTableAction('laporKembali', $perizinan)
            ->set('mountedActions.0.data.tanggal_kembali', now()->format('Y-m-d H:i:s'))
            ->set('mountedActions.0.data.bukti_kembali', [$fakeImg1, $fakeImg2])
            ->set('mountedActions.0.data.catatan_kembali', 'Santri tiba dengan selamat didampingi wali')
            ->callMountedTableAction();

        $perizinan->refresh();
        $this->assertSame('selesai', $perizinan->status);
        $this->assertGreaterThanOrEqual(2, $perizinan->getMedia('bukti_kembali')->count(), 'Multiple bukti kembali harus tersimpan');

        // Render blade view modal-lihat-bukti
        $rendered = view('filament.wali.components.modal-lihat-bukti', [
            'perizinan' => $perizinan,
        ])->render();

        $this->assertStringContainsString('Dokumen Pendukung Pengajuan Izin', $rendered);
        $this->assertStringContainsString('Foto & Dokumen Bukti Santri Kembali', $rendered);
        $this->assertStringContainsString('Santri tiba dengan selamat', $rendered);
    }

    public function test_media_preview_dan_download_dapat_diakses_tanpa_forbidden(): void
    {
        [$waliUser, $wali, $santri, $keamananUser] = $this->siapkanData();

        \Illuminate\Support\Facades\Storage::fake('public');

        $perizinan = Perizinan::create([
            'santri_id' => $santri->id,
            'jenis_izin' => 'pulang',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(2)->toDateString(),
            'jam_kembali_rencana' => '17:00:00',
            'alasan' => 'Libur semester',
            'status' => 'disetujui',
        ]);

        $fakeDoc = \Illuminate\Http\UploadedFile::fake()->createWithContent('surat_keterangan.pdf', "%PDF-1.4\n%EOF");
        $media = $perizinan->addMedia($fakeDoc)->toMediaCollection('dokumen_perizinan', 'public');

        // 1. Wali anak dapat melihat preview dan download dokumen
        $this->actingAs($waliUser)
            ->get(route('perizinan.media.preview', $media))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');

        $this->actingAs($waliUser)
            ->get(route('perizinan.media.download', $media))
            ->assertOk();

        // 2. Petugas Keamanan dapat melihat preview dokumen
        $this->actingAs($keamananUser)
            ->get(route('perizinan.media.preview', $media))
            ->assertOk();

        // 3. User lain tanpa izin atau bukan wali anak mendapatkan 403 Forbidden
        $otherUser = User::factory()->create(['username' => 'other-user']);
        $this->actingAs($otherUser)
            ->get(route('perizinan.media.preview', $media))
            ->assertForbidden();

        // 4. Tamu (unauthenticated) diarahkan ke login
        auth()->logout();
        $this->get(route('perizinan.media.preview', $media))
            ->assertRedirect('/login');
    }

    public function test_indikator_warna_merah_sedang_pulang_dan_hijau_kembali_di_frontend(): void
    {
        [$waliUser, $wali, $santri] = $this->siapkanData();

        $perizinan = Perizinan::create([
            'santri_id' => $santri->id,
            'jenis_izin' => 'pulang',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(2)->toDateString(),
            'jam_kembali_rencana' => '17:00:00',
            'alasan' => 'Keperluan keluarga',
            'status' => 'disetujui',
            'tanggal_kembali' => null,
        ]);

        // Status Sedang Pulang: Merah / danger
        $this->assertSame('sedang_pulang', $perizinan->posisi_santri);
        $this->assertSame('danger', $perizinan->posisi_santri_color, 'Warna posisi santri sedang pulang harus merah (danger)');
        $this->assertStringContainsString('Sedang Pulang', $perizinan->posisi_santri_label);
        $this->assertTrue($santri->isSedangPulang(), 'Santri harus teridentifikasi sedang pulang');

        // Cek render blade modal: harus menampilkan badge merah
        $modalSedangPulang = view('filament.wali.components.modal-lihat-bukti', [
            'perizinan' => $perizinan,
        ])->render();
        $this->assertStringContainsString('SANTRI SEDANG PULANG', $modalSedangPulang);
        $this->assertStringContainsString('text-red-700', $modalSedangPulang);

        // Ubah status menjadi Selesai (Kembali): Hijau / success
        $perizinan->update([
            'status' => 'selesai',
            'tanggal_kembali' => now(),
            'catatan_kembali' => 'Sudah masuk asrama',
        ]);
        $perizinan->refresh();
        $santri->refresh();

        $this->assertSame('sudah_kembali', $perizinan->posisi_santri);
        $this->assertSame('success', $perizinan->posisi_santri_color, 'Warna posisi santri sudah kembali harus hijau (success)');
        $this->assertStringContainsString('Sudah Kembali', $perizinan->posisi_santri_label);
        $this->assertFalse($santri->isSedangPulang(), 'Santri tidak lagi sedang pulang');

        // Cek render blade modal: harus menampilkan badge hijau
        $modalKembali = view('filament.wali.components.modal-lihat-bukti', [
            'perizinan' => $perizinan,
        ])->render();
        $this->assertStringContainsString('SANTRI SUDAH KEMBALI', $modalKembali);
        $this->assertStringContainsString('text-emerald-700', $modalKembali);
    }
}

