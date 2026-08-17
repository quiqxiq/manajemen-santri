<?php

namespace Tests\Feature;

use App\Filament\Resources\PembayaranResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FixRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_pembayaran_form_renders_without_select_readonly_error(): void
    {
        $admin = User::factory()->create(['username' => 'admin', 'password' => 'password']);
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role->givePermissionTo(Permission::create(['name' => 'ViewAny:Pembayaran', 'guard_name' => 'web']));
        $role->givePermissionTo(Permission::create(['name' => 'View:Pembayaran', 'guard_name' => 'web']));
        $role->givePermissionTo(Permission::create(['name' => 'Create:Pembayaran', 'guard_name' => 'web']));
        $role->givePermissionTo(Permission::create(['name' => 'Update:Pembayaran', 'guard_name' => 'web']));
        $role->givePermissionTo(Permission::create(['name' => 'Delete:Pembayaran', 'guard_name' => 'web']));
        $admin->assignRole($role);

        Livewire::actingAs($admin)
            ->test(PembayaranResource\Pages\ManagePembayarans::class)
            ->assertOk();
    }
}
