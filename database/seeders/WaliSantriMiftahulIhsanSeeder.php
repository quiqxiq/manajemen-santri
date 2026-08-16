<?php

namespace Database\Seeders;

use App\Models\Santri;
use App\Models\User;
use App\Models\WaliSantri;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WaliSantriMiftahulIhsanSeeder extends Seeder
{
    /**
     * Wali santri (orang tua) dari Data pondok pesantren Miftahul Ihsan.xls.
     * Satu akun dibuat per nomor WA wali; username = digit nomor HP, password default: password.
     */

    /** @var array<int, array{nama: string, phone: string, username: string, nis: string[]}> */
    protected array $waliData = [
        ['nama' => 'Subaidi', 'phone' => '0877-8722-4620', 'username' => '087787224620', 'nis' => ['20250001'],],
        ['nama' => 'Maskur', 'phone' => '0859-3433-5297', 'username' => '085934335297', 'nis' => ['20250002'],],
        ['nama' => 'Abd Rahman, S.Pd', 'phone' => '0859-2413-2383', 'username' => '085924132383', 'nis' => ['20250003'],],
        ['nama' => 'Aliman, S.Pd.I', 'phone' => '0878-5295-2495', 'username' => '087852952495', 'nis' => ['20250004'],],
        ['nama' => 'Marham', 'phone' => '0838-5073-3343', 'username' => '083850733343', 'nis' => ['20250005'],],
        ['nama' => 'Mohammad Zakaria', 'phone' => '0878-8683-3160', 'username' => '087886833160', 'nis' => ['20250006'],],
        ['nama' => 'Moh Haili Rifki', 'phone' => '0838-4996-6990', 'username' => '083849966990', 'nis' => ['20250007'],],
        ['nama' => 'Lutfi', 'phone' => '0878-8043-3119', 'username' => '087880433119', 'nis' => ['20250008'],],
        ['nama' => 'Ahmadi, S.Pd', 'phone' => '0878-3578-6241', 'username' => '087835786241', 'nis' => ['20250009'],],
        ['nama' => 'Moh Nur Hasan', 'phone' => '0819-3997-7423', 'username' => '081939977423', 'nis' => ['20250010'],],
        ['nama' => 'Darul Qutni', 'phone' => '0838-5416-2919', 'username' => '083854162919', 'nis' => ['20250011'],],
        ['nama' => 'Sudahri', 'phone' => '838-7340-0309', 'username' => '83873400309', 'nis' => ['20250012'],],
        ['nama' => 'Sahrul', 'phone' => '0877-8641-5273', 'username' => '087786415273', 'nis' => ['20250013'],],
        ['nama' => 'Razakki', 'phone' => '0878-3659-7428', 'username' => '087836597428', 'nis' => ['20250014'],],
        ['nama' => 'Sami\'uddin', 'phone' => '0877-5820-1423', 'username' => '087758201423', 'nis' => ['20250015'],],
        ['nama' => 'Wakirnoto', 'phone' => '0877-8880-9177', 'username' => '087788809177', 'nis' => ['20250016'],],
        ['nama' => 'Imam Hidayat', 'phone' => '0859-4762-3732', 'username' => '085947623732', 'nis' => ['20250017'],],
        ['nama' => 'Bahrudin, M.Pd.I', 'phone' => '0878-6323-9194', 'username' => '087863239194', 'nis' => ['20250018'],],
        ['nama' => 'Suharif', 'phone' => '0877-7943-4657', 'username' => '087779434657', 'nis' => ['20250019'],],
        ['nama' => 'Mu\'aris', 'phone' => '0877-5043-1672', 'username' => '087750431672', 'nis' => ['20250020'],],
        ['nama' => 'Ritno Efendi', 'phone' => '0878-5314-2783', 'username' => '087853142783', 'nis' => ['20250021'],],
        ['nama' => 'Abd. Walid', 'phone' => '819-3673-1546', 'username' => '81936731546', 'nis' => ['20250022'],],
        ['nama' => 'MULYADI, S.Pd.I', 'phone' => '0877-9471-3528', 'username' => '087794713528', 'nis' => ['20250023'],],
        ['nama' => 'MONIB (alm)', 'phone' => '0819-1117-1801', 'username' => '081911171801', 'nis' => ['20250024'],],
        ['nama' => 'SUHRI, S.Pd.I', 'phone' => '0818-0733-1388', 'username' => '081807331388', 'nis' => ['20250025'],],
        ['nama' => 'HABIBUDDIN', 'phone' => '0838-3534-2009', 'username' => '083835342009', 'nis' => ['20250026'],],
        ['nama' => 'MARHAM', 'phone' => '0819-9964-7323', 'username' => '081999647323', 'nis' => ['20250027'],],
        ['nama' => 'SAMSURI', 'phone' => '0819-9364-2751', 'username' => '081993642751', 'nis' => ['20250028'],],
        ['nama' => 'Adi Afriliyadi', 'phone' => '0858-5491-9130', 'username' => '085854919130', 'nis' => ['20250029'],],
        ['nama' => 'AS`ARI', 'phone' => '0878-4700-9354', 'username' => '087847009354', 'nis' => ['20250030'],],
        ['nama' => 'SULAIMAN', 'phone' => '0819-1629-6786', 'username' => '081916296786', 'nis' => ['20250031'],],
        ['nama' => 'IBNUH', 'phone' => '0877-5099-9322', 'username' => '087750999322', 'nis' => ['20250032'],],
        ['nama' => 'Mubdi', 'phone' => '0877-7776-7163', 'username' => '087777767163', 'nis' => ['20250033'],],
        ['nama' => 'RAWI', 'phone' => '0877-7638-6115', 'username' => '087776386115', 'nis' => ['20250034'],],
        ['nama' => 'LUQMAN', 'phone' => '0877-7638-4209', 'username' => '087776384209', 'nis' => ['20250035'],],
        ['nama' => 'SAHRUL', 'phone' => '0878-5185-1539', 'username' => '087851851539', 'nis' => ['20250036'],],
        ['nama' => 'FATHORRASYID', 'phone' => '0877-5922-6424', 'username' => '087759226424', 'nis' => ['20250037'],],
        ['nama' => 'Hamsus', 'phone' => '0877-5049-0830', 'username' => '087750490830', 'nis' => ['20250038'],],
        ['nama' => 'SAYUTI', 'phone' => '0877-5047-7740', 'username' => '087750477740', 'nis' => ['20250039'],],
        ['nama' => 'SAIFULLAH', 'phone' => '0852-3046-0603', 'username' => '085230460603', 'nis' => ['20250040'],],
        ['nama' => 'suparno', 'phone' => '0878-7909-1780', 'username' => '087879091780', 'nis' => ['20250041'],],
        ['nama' => 'IHSAN', 'phone' => '0831-9032-9069', 'username' => '083190329069', 'nis' => ['20250042'],],
        ['nama' => 'EDI HUSIN', 'phone' => '0859-6401-7151', 'username' => '085964017151', 'nis' => ['20250043'],],
        ['nama' => 'JAELANI', 'phone' => '0859-3026-1784', 'username' => '085930261784', 'nis' => ['20250044'],],
        ['nama' => 'FATHOR RASIK', 'phone' => '0878-5795-3696', 'username' => '087857953696', 'nis' => ['20250045'],],
        ['nama' => 'MATSAINI', 'phone' => '0819-3521-2025', 'username' => '081935212025', 'nis' => ['20250046'],],
        ['nama' => 'SAIFUL BAHRI', 'phone' => '0819-3839-5618', 'username' => '081938395618', 'nis' => ['20250047'],],
        ['nama' => 'SAHLIYA', 'phone' => '0819-1089-0211', 'username' => '081910890211', 'nis' => ['20250048'],],
        ['nama' => 'ATUP', 'phone' => '0851-1974-5937', 'username' => '085119745937', 'nis' => ['20250049'],],
        ['nama' => 'Mu\'aris', 'phone' => '0877-3401-1133', 'username' => '087734011133', 'nis' => ['20250050'],],
        ['nama' => 'ABU SALIM', 'phone' => '0877-7459-7768', 'username' => '087774597768', 'nis' => ['20250051'],],
        ['nama' => 'KUTWA ASSAFU', 'phone' => '0838-3013-0892', 'username' => '083830130892', 'nis' => ['20250052'],],
        ['nama' => 'SAIFUL', 'phone' => '0878-6105-4073', 'username' => '087861054073', 'nis' => ['20250053'],],
        ['nama' => 'BUASAN', 'phone' => '0877-7936-2148', 'username' => '087779362148', 'nis' => ['20250054'],],
        ['nama' => 'NADARI', 'phone' => '0878-5744-4581', 'username' => '087857444581', 'nis' => ['20250055'],],
        ['nama' => 'MUZAKKI', 'phone' => '0878-2461-2524', 'username' => '087824612524', 'nis' => ['20250056'],],
    ];

    public function run(): void
    {
        foreach ($this->waliData as $w) {
            $user = User::firstOrCreate(
                ['username' => $w['username']],
                [
                    'name' => $w['nama'],
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ],
            );
            $user->assignRole('Wali Santri');

            $wali = WaliSantri::firstOrCreate(
                ['user_id' => $user->id],
                ['nama' => $w['nama'], 'no_hp' => $w['phone']],
            );

            foreach ($w['nis'] as $nis) {
                $santri = Santri::where('nis', $nis)->first();
                if (! $santri) {
                    $this->command?->warn("Santri $nis tidak ditemukan.");
                    continue;
                }
                $santri->waliSantri()->syncWithoutDetaching([
                    $wali->id => ['hubungan' => 'ayah', 'is_penanggung_jawab_utama' => true],
                ]);
            }
        }

        $this->command?->info('Seeded ' . count($this->waliData) . ' akun wali Miftahul Ihsan.');
    }
}
