<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->bersihkanRoleLama();
        $this->hapusPermissionBasi();

        // Set permission per entitas yang masih dipakai.
        $crud = fn (string $e): array => [
            "ViewAny:{$e}", "View:{$e}", "Create:{$e}", "Update:{$e}", "Delete:{$e}",
            "DeleteAny:{$e}", "ForceDelete:{$e}", "ForceDeleteAny:{$e}",
            "Restore:{$e}", "RestoreAny:{$e}", "Replicate:{$e}", "Reorder:{$e}",
        ];
        $view = fn (string $e): array => ["ViewAny:{$e}", "View:{$e}"];

        $rolesPermissions = [
            // Keuangan: mengelola tagihan, pembayaran, serta melihat data santri & log notifikasi.
            'Keuangan' => [
                ...$view('Santri'), ...$view('WaliSantri'),
                ...$crud('Tagihan'), ...$crud('Pembayaran'),
                ...$view('NotifikasiLog'),
            ],

            // Pengasuh: hanya baca (read only) untuk monitoring santri & asrama.
            'Pengasuh' => [
                ...$view('Santri'), ...$view('WaliSantri'), ...$view('Kamar'),
                ...$view('KategoriPelanggaran'), ...$view('Pelanggaran'), ...$view('Penghargaan'),
                ...$view('Tagihan'), ...$view('Pembayaran'), ...$view('Perizinan'),
                ...$view('Pengurus'), ...$view('Tahfidz'), ...$view('NotifikasiLog'),
            ],

            // Keamanan: pelanggaran, kategori pelanggaran, dan perizinan.
            'Keamanan' => [
                ...$view('Santri'), ...$view('KategoriPelanggaran'),
                ...$crud('Pelanggaran'), ...$crud('KategoriPelanggaran'),
                ...$view('Perizinan'), 'Create:Perizinan', 'Update:Perizinan',
            ],

            // Wali Santri: lihat data anak sendiri (di-scope di panel wali)
            // + mengajukan perizinan sendiri dari portal wali.
            'Wali Santri' => [
                ...$view('Santri'), ...$view('Pelanggaran'), ...$view('Tagihan'),
                ...$view('Tahfidz'), ...$view('Perizinan'), 'Create:Perizinan',
            ],
        ];

        foreach ($rolesPermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            foreach ($permissions as $permissionName) {
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            }

            $role->syncPermissions($permissions);
        }

        // Admin: akses penuh ke SEMUA permission yang ada saat ini
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all()->pluck('name')->toArray());

        $users = [
            ['username' => 'admin', 'name' => 'Administrator', 'email' => 'admin@miftahulihsan.sch.id', 'role' => 'Admin'],
            ['username' => 'keuangan', 'name' => 'Staf Keuangan', 'email' => 'keuangan@miftahulihsan.sch.id', 'role' => 'Keuangan'],
            ['username' => 'keamanan', 'name' => 'Pengurus Keamanan', 'email' => 'keamanan@miftahulihsan.sch.id', 'role' => 'Keamanan'],
            ['username' => 'ustadz', 'name' => 'Ustadz Ahmad', 'email' => 'ustadz@miftahulihsan.sch.id', 'role' => 'Admin'],
            ['username' => 'pengasuh', 'name' => 'KH. Abdullah (Pengasuh)', 'email' => 'pengasuh@miftahulihsan.sch.id', 'role' => 'Pengasuh'],
            ['username' => 'wali', 'name' => 'Wali Santri Demo', 'email' => 'wali@miftahulihsan.sch.id', 'role' => 'Wali Santri'],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['username' => $userData['username']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            );

            $user->syncRoles([$userData['role']]);
        }

        // Portal santri tidak dipakai lagi — hapus user demo santri.
        User::where('username', 'santri')->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Pindahkan user/permission dari role lama ke role baru, lalu hapus role lama.
     */
    private function bersihkanRoleLama(): void
    {
        $rename = [
            'Super Admin' => 'Admin',
            'super_admin' => 'Admin',
            'Admin/Tata Usaha' => 'Admin',
            'Bagian Keuangan' => 'Keuangan',
            'Ustadz/Guru' => 'Admin',
            'Pengurus Keamanan' => 'Keamanan',
            'Operator' => 'Admin',
        ];

        foreach ($rename as $old => $new) {
            $oldRole = Role::where('name', $old)->first();

            if (! $oldRole) {
                continue;
            }

            $newRole = Role::firstOrCreate(['name' => $new, 'guard_name' => 'web']);

            if ($oldRole->id === $newRole->id) {
                continue;
            }

            DB::table('model_has_roles')
                ->where('role_id', $oldRole->id)
                ->update(['role_id' => $newRole->id]);

            foreach (DB::table('role_has_permissions')->where('role_id', $oldRole->id)->pluck('permission_id') as $permissionId) {
                DB::table('role_has_permissions')->updateOrInsert(
                    ['permission_id' => $permissionId, 'role_id' => $newRole->id],
                    []
                );
            }

            $oldRole->delete();
        }

        foreach (['Santri', 'Operator', 'Super Admin', 'super_admin'] as $obsolete) {
            Role::where('name', $obsolete)->first()?->delete();
        }
    }

    /**
     * Hapus permission milik entitas yang sudah tidak ada lagi.
     */
    private function hapusPermissionBasi(): void
    {
        Permission::where('name', 'like', '%MataPelajaran%')
            ->orWhere('name', 'like', '%NilaiAkademik%')
            ->orWhere('name', 'like', '%PenyakitBawaan%')
            ->orWhere('name', 'like', '%RiwayatKesehatan%')
            ->delete();
    }
}
