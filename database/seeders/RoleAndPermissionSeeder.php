<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesPermissions = [
            'Super Admin' => Permission::all()->pluck('name')->toArray(),
            'Admin/Tata Usaha' => [
                'ViewAny:Santri', 'View:Santri', 'Create:Santri', 'Update:Santri',
                'ViewAny:WaliSantri', 'View:WaliSantri', 'Create:WaliSantri', 'Update:WaliSantri',
                'ViewAny:Kamar', 'View:Kamar', 'Create:Kamar', 'Update:Kamar',
                'ViewAny:MataPelajaran', 'View:MataPelajaran', 'Create:MataPelajaran', 'Update:MataPelajaran',
                'ViewAny:KategoriPelanggaran', 'View:KategoriPelanggaran',
                'ViewAny:Pengurus', 'View:Pengurus',
            ],
            'Bagian Keuangan' => [
                'ViewAny:Tagihan', 'View:Tagihan', 'Create:Tagihan', 'Update:Tagihan',
                'ViewAny:Pembayaran', 'View:Pembayaran', 'Create:Pembayaran', 'Update:Pembayaran',
                'ViewAny:Santri', 'View:Santri',
            ],
            'Pengurus Keamanan' => [
                'ViewAny:Pelanggaran', 'View:Pelanggaran', 'Create:Pelanggaran', 'Update:Pelanggaran',
                'ViewAny:KategoriPelanggaran', 'View:KategoriPelanggaran',
                'ViewAny:Perizinan', 'View:Perizinan', 'Create:Perizinan', 'Update:Perizinan',
            ],
            'Ustadz/Guru' => [
                'ViewAny:NilaiAkademik', 'View:NilaiAkademik', 'Create:NilaiAkademik', 'Update:NilaiAkademik',
                'ViewAny:Tahfidz', 'View:Tahfidz', 'Create:Tahfidz', 'Update:Tahfidz',
                'ViewAny:Santri', 'View:Santri',
            ],
            'Pengasuh' => [
                'ViewAny:Pelanggaran', 'View:Pelanggaran', 'Update:Pelanggaran',
                'ViewAny:Penghargaan', 'View:Penghargaan', 'Create:Penghargaan', 'Update:Penghargaan',
                'ViewAny:Perizinan', 'View:Perizinan', 'Update:Perizinan',
                'ViewAny:RiwayatKesehatan', 'View:RiwayatKesehatan',
                'ViewAny:PenyakitBawaan', 'View:PenyakitBawaan',
            ],
            'Wali Santri' => [
                'ViewAny:Santri', 'View:Santri',
                'ViewAny:NilaiAkademik', 'View:NilaiAkademik',
                'ViewAny:Tahfidz', 'View:Tahfidz',
                'ViewAny:Pelanggaran', 'View:Pelanggaran',
                'ViewAny:Tagihan', 'View:Tagihan',
                'ViewAny:Perizinan', 'View:Perizinan', 'Create:Perizinan',
            ],
            'Santri' => [
                'ViewAny:NilaiAkademik', 'View:NilaiAkademik',
                'ViewAny:Tahfidz', 'View:Tahfidz',
                'ViewAny:Perizinan', 'View:Perizinan', 'Create:Perizinan',
            ],
        ];

        foreach ($rolesPermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            // Sync existing permissions only
            $existingPermissions = Permission::whereIn('name', $permissions)->get();
            $role->syncPermissions($existingPermissions);
        }

        $users = [
            ['username' => 'admin', 'name' => 'Super Administrator', 'email' => 'admin@miftahulihsan.sch.id', 'role' => 'Super Admin'],
            ['username' => 'tatausaha', 'name' => 'Staf Tata Usaha', 'email' => 'tu@miftahulihsan.sch.id', 'role' => 'Admin/Tata Usaha'],
            ['username' => 'keuangan', 'name' => 'Staf Keuangan', 'email' => 'keuangan@miftahulihsan.sch.id', 'role' => 'Bagian Keuangan'],
            ['username' => 'keamanan', 'name' => 'Pengurus Keamanan', 'email' => 'keamanan@miftahulihsan.sch.id', 'role' => 'Pengurus Keamanan'],
            ['username' => 'ustadz', 'name' => 'Ustadz Ahmad', 'email' => 'ustadz@miftahulihsan.sch.id', 'role' => 'Ustadz/Guru'],
            ['username' => 'pengasuh', 'name' => 'KH. Abdullah (Pengasuh)', 'email' => 'pengasuh@miftahulihsan.sch.id', 'role' => 'Pengasuh'],
            ['username' => 'wali', 'name' => 'Wali Santri Demo', 'email' => 'wali@miftahulihsan.sch.id', 'role' => 'Wali Santri'],
            ['username' => 'santri', 'name' => 'Santri Demo', 'email' => 'santri@miftahulihsan.sch.id', 'role' => 'Santri'],
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
    }
}
