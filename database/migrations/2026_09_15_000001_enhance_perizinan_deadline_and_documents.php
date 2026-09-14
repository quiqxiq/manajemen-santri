<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Pembaruan tabel perizinan dan sistem notifikasi:
 *  - jam_kembali_rencana : jam rencana kedatangan santri kembali di pondok (default 17:00:00)
 *  - pengingat_kembali_sent_at : waktu pengiriman notifikasi pengingat kembali ke wali
 *  - template WhatsApp perizinan_pengingat_kembali untuk pengingat tenggat waktu ke wali
 *  - tabel notifications Laravel untuk in-app database notifications Filament
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        Schema::table('perizinan', function (Blueprint $table) {
            $table->time('jam_kembali_rencana')->nullable()->default('17:00:00')->after('tanggal_selesai');
            $table->dateTime('pengingat_kembali_sent_at')->nullable()->after('catatan_kembali');
        });

        DB::table('whatsapp_templates')->insertOrIgnore([
            [
                'nama' => 'perizinan_pengingat_kembali',
                'judul' => 'Pengingat Batas Waktu Kepulangan Santri (Ke Wali Santri)',
                'pesan' => "*Pengingat Batas Waktu Perizinan Santri*\n\nAssalamu'alaikum Warahmatullahi Wabarakatuh,\nBapak/Ibu {nama_wali},\n\nMengingatkan kembali bahwa masa perizinan santri berikut:\n• *Nama Santri*: {nama_santri} ({kamar_santri})\n• *Jenis Izin*: {jenis_izin}\n• *Batas Kepulangan*: {tanggal_selesai} pukul {jam_kembali} WIB\n• *Sisa Waktu*: {sisa_waktu}\n\nHarap ananda dipersiapkan untuk kembali ke pondok pesantren tepat waktu sesuai ketentuan.\n\nSetelah tiba di pondok, mohon laporkan kepulangan dan unggah foto bukti kedatangan melalui portal wali santri.\n\nTerima kasih.\nWassalamu'alaikum Warahmatullahi Wabarakatuh,\nPos Keamanan Pondok Pesantren Miftahul Ihsan",
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->migrator->add('whatsapp.template_perizinan_pengingat_kembali', 'perizinan_pengingat_kembali');

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('perizinan', function (Blueprint $table) {
            $table->dropColumn(['jam_kembali_rencana', 'pengingat_kembali_sent_at']);
        });

        DB::table('whatsapp_templates')
            ->where('nama', 'perizinan_pengingat_kembali')
            ->delete();

        $this->migrator->delete('whatsapp.template_perizinan_pengingat_kembali');
    }
};
