<?php

namespace Tests\Feature;

use App\Filament\Wali\Resources\PerizinanResource\Pages\ManagePerizinans;
use App\Models\Kamar;
use App\Models\Perizinan;
use App\Models\Santri;
use App\Models\User;
use App\Models\WaliSantri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WaliPerizinanTest extends TestCase
{
    use RefreshDatabase;

    private function buatWaliDenganAnak(): array
    {
        $waliUser = User::factory()->create(['username' => 'wali-test', 'password' => 'password']);
        $role = Role::create(['name' => 'Wali Santri', 'guard_name' => 'web']);
        foreach (['ViewAny:Perizinan', 'View:Perizinan', 'Create:Perizinan'] as $perm) {
            $role->givePermissionTo(Permission::create(['name' => $perm, 'guard_name' => 'web']));
        }
        $waliUser->assignRole($role);

        $wali = WaliSantri::create([
            'user_id' => $waliUser->id,
            'nama' => 'Bapak Wali',
            'no_hp' => '081234567890',
        ]);

        $kamar = Kamar::create(['nama_kamar' => 'Kamar Test', 'kapasitas' => 10]);
        $santri = Santri::create([
            'nis' => 'TS-001',
            'nama_lengkap' => 'Anak Santri',
            'tempat_lahir' => 'Sumenep',
            'tanggal_lahir' => '2013-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Bluto',
            'asal_sekolah' => 'MI',
            'kamar_id' => $kamar->id,
            'status' => 'aktif',
            'tanggal_masuk' => '2025-07-10',
        ]);
        $santri->waliSantri()->attach($wali->id, [
            'hubungan' => 'ayah',
            'is_penanggung_jawab_utama' => true,
        ]);

        return [$waliUser, $santri];
    }

    public function test_wali_dapat_mengajukan_perizinan_dari_portal(): void
    {
        [$waliUser, $santri] = $this->buatWaliDenganAnak();

        $test = Livewire::actingAs($waliUser)
            ->test(ManagePerizinans::class)
            ->mountAction('create');

        $test->assertActionMounted('create');

        $test->set('mountedActions.0.data.santri_id', $santri->id);
        $test->set('mountedActions.0.data.jenis_izin', 'acara_keluarga');
        $test->set('mountedActions.0.data.tanggal_mulai', now()->toDateString());
        $test->set('mountedActions.0.data.tanggal_selesai', now()->addDays(2)->toDateString());
        $test->set('mountedActions.0.data.alasan', 'Menghadiri acara keluarga');

        $test->callMountedAction()
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('perizinan', [
            'santri_id' => $santri->id,
            'jenis_izin' => 'acara_keluarga',
            'status' => 'diajukan',
            'alasan' => 'Menghadiri acara keluarga',
        ]);
    }

    public function test_pengajuan_wali_muncul_di_resource_admin(): void
    {
        [$waliUser, $santri] = $this->buatWaliDenganAnak();

        Livewire::actingAs($waliUser)
            ->test(ManagePerizinans::class)
            ->mountAction('create')
            ->set('mountedActions.0.data.santri_id', $santri->id)
            ->set('mountedActions.0.data.jenis_izin', 'pulang')
            ->set('mountedActions.0.data.tanggal_mulai', now()->toDateString())
            ->set('mountedActions.0.data.tanggal_selesai', now()->addDays(1)->toDateString())
            ->set('mountedActions.0.data.alasan', 'Pulang ke rumah')
            ->callMountedAction();

        $perizinan = Perizinan::first();
        $this->assertNotNull($perizinan);
        $this->assertEquals('diajukan', $perizinan->status);
        $this->assertSame($santri->id, $perizinan->santri_id);
    }
}
