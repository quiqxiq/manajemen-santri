<?php

namespace Database\Seeders;

use App\Models\Kamar;
use App\Models\Santri;
use Illuminate\Database\Seeder;

class SantriMiftahulIhsanSeeder extends Seeder
{
    /**
     * Data santri Yayasan Pesantren Miftahul Ihsan, Errabu Bluto Sumenep,
     * Tahun Pelajaran 2025-2026 — diekstrak dari Data pondok pesantren Miftahul Ihsan.xls.
     */

    /** @var array<int, array{kode: string, nama: string}> */
    protected array $kamarData = [
        ['kode' => 'PP1', 'nama' => 'Kamar Al-Falah'],
        ['kode' => 'PP2', 'nama' => 'Kamar Al-Hikmah'],
        ['kode' => 'PP3', 'nama' => 'Kamar Al-Ikhlas'],
        ['kode' => 'PP4', 'nama' => 'Kamar Al-Muttaqin'],
        ['kode' => 'PP5', 'nama' => 'Kamar Al-Muhajirin'],
    ];

    /** @var array<int, array<string, string|null>> */
    protected array $santriData = [
        ['nis' => '20250001', 'nama_lengkap' => 'Ahmad Faiq Ramadani', 'tempat_lahir' => 'Sumenep', 'tanggal_lahir' => '2013-10-29', 'jenis_kelamin' => 'L', 'alamat' => 'Kapedi Bluto Sumenep', 'asal_sekolah' => 'MI', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250002', 'nama_lengkap' => 'M Fatir Syahrul A\'la', 'tempat_lahir' => 'Pamekasan', 'tanggal_lahir' => '2013-01-16', 'jenis_kelamin' => 'L', 'alamat' => 'Kapedi Bluto Sumenep', 'asal_sekolah' => 'MI', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250003', 'nama_lengkap' => 'Moh Azkal Ibadi', 'tempat_lahir' => 'Sumenep', 'tanggal_lahir' => '2014-10-16', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MI', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250004', 'nama_lengkap' => 'Nafilurraohman', 'tempat_lahir' => 'Sumenep', 'tanggal_lahir' => '2013-02-24', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MI', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250005', 'nama_lengkap' => 'Ifan Mustofa Kamil', 'tempat_lahir' => 'Sumenep', 'tanggal_lahir' => '2013-06-21', 'jenis_kelamin' => 'L', 'alamat' => 'Kapedi Bluto Sumenep', 'asal_sekolah' => 'MI', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250006', 'nama_lengkap' => 'Moh Azkal Khair', 'tempat_lahir' => 'Sumenep', 'tanggal_lahir' => '2013-03-26', 'jenis_kelamin' => 'L', 'alamat' => 'Kapedi Bluto Sumenep', 'asal_sekolah' => 'MI', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250007', 'nama_lengkap' => 'Muhammad Azriel Amirullah', 'tempat_lahir' => 'Pamekasan', 'tanggal_lahir' => '2013-12-03', 'jenis_kelamin' => 'L', 'alamat' => 'Gili Genting Sumenep', 'asal_sekolah' => 'MI', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250008', 'nama_lengkap' => 'Muhammad Chairil Rafky', 'tempat_lahir' => 'Sumenep', 'tanggal_lahir' => '2013-07-22', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MI', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250009', 'nama_lengkap' => 'Muhammad Yazdan Mafaza Ahmadi', 'tempat_lahir' => 'Sumenep', 'tanggal_lahir' => '2013-08-01', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MI', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250010', 'nama_lengkap' => 'Ubay Dillah', 'tempat_lahir' => 'Sumenep', 'tanggal_lahir' => '2009-12-08', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250011', 'nama_lengkap' => 'Mohammad Abdullah Faqih', 'tempat_lahir' => 'Sumenep', 'tanggal_lahir' => '2010-09-21', 'jenis_kelamin' => 'L', 'alamat' => 'Kangean Arjasa Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250012', 'nama_lengkap' => 'Rendi Pangalila', 'tempat_lahir' => 'Sumenep', 'tanggal_lahir' => '2010-11-19', 'jenis_kelamin' => 'L', 'alamat' => 'Kangean Arjasa Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250013', 'nama_lengkap' => 'Irwanto', 'tempat_lahir' => 'Sumenep', 'tanggal_lahir' => '2010-02-03', 'jenis_kelamin' => 'L', 'alamat' => 'Kangean Arjasa Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250014', 'nama_lengkap' => 'ACHMAD FIRDAUZI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-12-09', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250015', 'nama_lengkap' => 'Kafa Billah', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2009-11-27', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250016', 'nama_lengkap' => 'Khairul Faqih', 'tempat_lahir' => 'PAMEKASAN', 'tanggal_lahir' => '2010-09-14', 'jenis_kelamin' => 'L', 'alamat' => 'Tobungan Galis Pamekasan', 'asal_sekolah' => 'MTs', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250017', 'nama_lengkap' => 'IMAM BASORI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-11-17', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250018', 'nama_lengkap' => 'MAHATIR NURI TAWALI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-03-13', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250019', 'nama_lengkap' => 'MOH. BARIK AZIZAN', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-04-14', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250020', 'nama_lengkap' => 'MOHAMMAD ROFIQI', 'tempat_lahir' => 'PAMEKASAN', 'tanggal_lahir' => '2010-01-07', 'jenis_kelamin' => 'L', 'alamat' => 'Kapedi Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250021', 'nama_lengkap' => 'MUHAMMAD SHAFWAN HAIDAR', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2009-07-16', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP4', 'status' => 'aktif',],
        ['nis' => '20250022', 'nama_lengkap' => 'Salman Al Farizi', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2011-10-10', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP4', 'status' => 'aktif',],
        ['nis' => '20250023', 'nama_lengkap' => 'Eko Wahyudi', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-06-15', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP5', 'status' => 'aktif',],
        ['nis' => '20250024', 'nama_lengkap' => 'MAUROBI', 'tempat_lahir' => 'PAMEKASAN', 'tanggal_lahir' => '2010-03-26', 'jenis_kelamin' => 'L', 'alamat' => 'Tobungan Galis Pamekasan', 'asal_sekolah' => 'MTs', 'kamar' => 'PP5', 'status' => 'aktif',],
        ['nis' => '20250025', 'nama_lengkap' => 'NABHAN FAIRUZ ZAKA', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-05-22', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP5', 'status' => 'aktif',],
        ['nis' => '20250026', 'nama_lengkap' => 'Yudis Tira Maulana Putra', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-04-29', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP5', 'status' => 'aktif',],
        ['nis' => '20250027', 'nama_lengkap' => 'Fachroni Alfiansyah', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-07-08', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP5', 'status' => 'aktif',],
        ['nis' => '20250028', 'nama_lengkap' => 'ACH. WILDAN KAROMI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2009-12-06', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250029', 'nama_lengkap' => 'DARUL FIRDAUSI ALFIRMANSYAH', 'tempat_lahir' => 'PAMEKASAN', 'tanggal_lahir' => '2010-07-09', 'jenis_kelamin' => 'L', 'alamat' => 'Kalisangka Arjasa Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250030', 'nama_lengkap' => 'FARHAN AL FARIZI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-06-09', 'jenis_kelamin' => 'L', 'alamat' => 'Moncek Timur Lenteng Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250031', 'nama_lengkap' => 'Habiburrahman', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-08-05', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250032', 'nama_lengkap' => 'Umar Faruq', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-07-30', 'jenis_kelamin' => 'L', 'alamat' => 'Gilang Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250033', 'nama_lengkap' => 'MOH. ANDI FADHLY AL-MAGHROBY', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2011-03-15', 'jenis_kelamin' => 'L', 'alamat' => 'Moncek Tengah Lenteng Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250034', 'nama_lengkap' => 'MOH. IQRO` AL GHIFARI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-03-17', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP5', 'status' => 'aktif',],
        ['nis' => '20250035', 'nama_lengkap' => 'MOH. NAJA IMALANA', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2011-04-05', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250036', 'nama_lengkap' => 'MUHAMMAD ALI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-07-27', 'jenis_kelamin' => 'L', 'alamat' => 'Gilang Bluto Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP5', 'status' => 'aktif',],
        ['nis' => '20250037', 'nama_lengkap' => 'SYAIFUL HADI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2011-03-23', 'jenis_kelamin' => 'L', 'alamat' => 'Moncek Tengah Lenteng Sumenep', 'asal_sekolah' => 'MTs', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250038', 'nama_lengkap' => 'USNALDY', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2010-11-06', 'jenis_kelamin' => 'L', 'alamat' => 'Kapedi Bluto Sumenep', 'asal_sekolah' => null, 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250039', 'nama_lengkap' => 'AHMAD AZIZ FUADI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2007-08-28', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250040', 'nama_lengkap' => 'AHMAD FAWAID', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2007-10-06', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP5', 'status' => 'keluar',],
        ['nis' => '20250041', 'nama_lengkap' => 'ALI FIKRI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2008-05-17', 'jenis_kelamin' => 'L', 'alamat' => 'Kapedi Bluto Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP4', 'status' => 'aktif',],
        ['nis' => '20250042', 'nama_lengkap' => 'ARIZANDI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2008-06-19', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250043', 'nama_lengkap' => 'Rusdianto', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2007-04-28', 'jenis_kelamin' => 'L', 'alamat' => 'Kalisangka Arjasa Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250044', 'nama_lengkap' => 'Luqman Hakim', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2008-08-30', 'jenis_kelamin' => 'L', 'alamat' => 'Kalisangka Arjasa Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP2', 'status' => 'aktif',],
        ['nis' => '20250045', 'nama_lengkap' => 'FAREL EFENDI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2008-04-29', 'jenis_kelamin' => 'L', 'alamat' => 'Kalisangka Arjasa Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250046', 'nama_lengkap' => 'Ach Luay Habibi', 'tempat_lahir' => 'SURABAYA', 'tanggal_lahir' => '2006-08-09', 'jenis_kelamin' => 'L', 'alamat' => 'Sawunggaling Wonokromo Surabaya', 'asal_sekolah' => 'MA', 'kamar' => 'PP4', 'status' => 'aktif',],
        ['nis' => '20250047', 'nama_lengkap' => 'Abd Mannan', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2007-06-04', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP4', 'status' => 'aktif',],
        ['nis' => '20250048', 'nama_lengkap' => 'Hairul Basyar', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2007-05-24', 'jenis_kelamin' => 'L', 'alamat' => 'Moncek Timur Lenteng Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP5', 'status' => 'aktif',],
        ['nis' => '20250049', 'nama_lengkap' => 'Maulana Malik Ibrohim Asmara Kondi', 'tempat_lahir' => 'SURABAYA', 'tanggal_lahir' => '2007-01-19', 'jenis_kelamin' => 'L', 'alamat' => 'Sawunggaling Wonokromo Surabaya', 'asal_sekolah' => 'MA', 'kamar' => 'PP1', 'status' => 'aktif',],
        ['nis' => '20250050', 'nama_lengkap' => 'MOH. RIZQI', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2007-09-05', 'jenis_kelamin' => 'L', 'alamat' => 'Kapedi Bluto Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP5', 'status' => 'aktif',],
        ['nis' => '20250051', 'nama_lengkap' => 'NABIL HIDAYATURRAHMAN', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2007-12-12', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250052', 'nama_lengkap' => 'Moh Ramsi', 'tempat_lahir' => 'SURABAYA', 'tanggal_lahir' => '2007-11-30', 'jenis_kelamin' => 'L', 'alamat' => 'Sawunggaling Wonokromo Surabaya', 'asal_sekolah' => 'MA', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250053', 'nama_lengkap' => 'Rizqi Jailani', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2008-03-02', 'jenis_kelamin' => 'L', 'alamat' => 'Moncek Timur Lenteng Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP3', 'status' => 'aktif',],
        ['nis' => '20250054', 'nama_lengkap' => 'Rasidi', 'tempat_lahir' => 'PAMEKASAN', 'tanggal_lahir' => '2008-09-27', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP4', 'status' => 'aktif',],
        ['nis' => '20250055', 'nama_lengkap' => 'Abdul Basit', 'tempat_lahir' => 'SUMENEP', 'tanggal_lahir' => '2006-03-04', 'jenis_kelamin' => 'L', 'alamat' => 'Moncek Timur Lenteng Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP4', 'status' => 'aktif',],
        ['nis' => '20250056', 'nama_lengkap' => 'WAHYU FIRMAN FIRDAUS', 'tempat_lahir' => 'PAMEKASAN', 'tanggal_lahir' => '2008-01-24', 'jenis_kelamin' => 'L', 'alamat' => 'Errabu Bluto Sumenep', 'asal_sekolah' => 'MA', 'kamar' => 'PP2', 'status' => 'aktif',],
    ];

    public function run(): void
    {
        $kamar = [];
        foreach ($this->kamarData as $k) {
            $kamar[$k['kode']] = Kamar::firstOrCreate(
                ['nama_kamar' => $k['nama']],
                ['kapasitas' => 20, 'keterangan' => 'Kode ' . $k['kode'] . ' — data impor Miftahul Ihsan'],
            );
        }

        foreach ($this->santriData as $s) {
            Santri::firstOrCreate(
                ['nis' => $s['nis']],
                [
                    'nama_lengkap' => $s['nama_lengkap'],
                    'tempat_lahir' => $s['tempat_lahir'],
                    'tanggal_lahir' => $s['tanggal_lahir'],
                    'jenis_kelamin' => $s['jenis_kelamin'],
                    'alamat' => $s['alamat'],
                    'asal_sekolah' => $s['asal_sekolah'],
                    'kamar_id' => $s['kamar'] ? ($kamar[$s['kamar']]->id ?? null) : null,
                    'status' => $s['status'],
                    'tanggal_masuk' => '2025-07-14', // kolom 'Tahun Masuk' kosong di Excel; TP 2025-2026
                ],
            );
        }

        $this->command?->info('Seeded ' . count($this->santriData) . ' santri Miftahul Ihsan.');
    }
}
