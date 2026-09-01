<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserAuthAndRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_super_admin_and_operator_roles_do_not_exist(): void
    {
        $this->assertFalse(Role::where('name', 'Super Admin')->orWhere('name', 'super_admin')->exists());
        $this->assertFalse(Role::where('name', 'Operator')->exists());
    }

    public function test_expected_roles_exist(): void
    {
        $this->assertTrue(Role::where('name', 'Admin')->exists());
        $this->assertTrue(Role::where('name', 'Keuangan')->exists());
        $this->assertTrue(Role::where('name', 'Pengasuh')->exists());
        $this->assertTrue(Role::where('name', 'Keamanan')->exists());
        $this->assertTrue(Role::where('name', 'Wali Santri')->exists());
    }

    public function test_penyakit_and_riwayat_kesehatan_tables_are_removed(): void
    {
        $this->assertFalse(Schema::hasTable('penyakit_bawaan'));
        $this->assertFalse(Schema::hasTable('riwayat_kesehatan'));
    }

    public function test_active_admin_and_keuangan_can_access_admin_panel(): void
    {
        $admin = User::where('username', 'admin')->first();
        $keuangan = User::where('username', 'keuangan')->first();
        $pengasuh = User::where('username', 'pengasuh')->first();
        $keamanan = User::where('username', 'keamanan')->first();

        $adminPanel = Filament::getPanel('admin');

        $this->assertTrue($admin->canAccessPanel($adminPanel));
        $this->assertTrue($keuangan->canAccessPanel($adminPanel));
        $this->assertTrue($pengasuh->canAccessPanel($adminPanel));
        $this->assertTrue($keamanan->canAccessPanel($adminPanel));
    }

    public function test_inactive_user_cannot_access_panel(): void
    {
        $inactiveUser = User::create([
            'name' => 'Inactive User',
            'username' => 'inactive_user',
            'email' => 'inactive@test.com',
            'password' => Hash::make('password'),
            'is_active' => false,
        ]);
        $inactiveUser->assignRole('Admin');

        $adminPanel = Filament::getPanel('admin');
        $this->assertFalse($inactiveUser->canAccessPanel($adminPanel));
    }

    public function test_user_without_roles_cannot_access_panel(): void
    {
        $noRoleUser = User::create([
            'name' => 'No Role User',
            'username' => 'norole_user',
            'email' => 'norole@test.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $adminPanel = Filament::getPanel('admin');
        $this->assertFalse($noRoleUser->canAccessPanel($adminPanel));
    }

    public function test_wali_santri_can_access_wali_panel(): void
    {
        $wali = User::where('username', 'wali')->first();
        $waliPanel = Filament::getPanel('wali');
        $adminPanel = Filament::getPanel('admin');

        $this->assertTrue($wali->canAccessPanel($waliPanel));
        $this->assertFalse($wali->canAccessPanel($adminPanel));
    }

    public function test_newly_created_user_with_role_can_access_panel(): void
    {
        $newUser = User::create([
            'name' => 'Staf Baru',
            'username' => 'stafbaru',
            'email' => 'stafbaru@miftahulihsan.sch.id',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $newUser->syncRoles(['Keuangan']);

        $adminPanel = Filament::getPanel('admin');
        $this->assertTrue($newUser->canAccessPanel($adminPanel));
        $this->assertTrue(Hash::check('password123', $newUser->password));
    }
}
