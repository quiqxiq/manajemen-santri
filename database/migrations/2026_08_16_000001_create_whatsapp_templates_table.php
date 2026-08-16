<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('judul');
            $table->text('pesan');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // Template default untuk aturan R2 (notifikasi pelanggaran ke wali santri).
        // Placeholder yang didukung: {nama_santri}, {nama_kategori}, {poin}, {total_poin}, {nama_wali}
        DB::table('whatsapp_templates')->insert([
            'nama' => 'pelanggaran_peringatan',
            'judul' => 'Pemberitahuan Pelanggaran (R2)',
            'pesan' => "Pemberitahuan Pelanggaran:\nSantri {nama_santri} mencatatkan pelanggaran \"{nama_kategori}\" (+{poin} poin). Total akumulasi poin saat ini: {total_poin}.\n\nHormat kami,\nPondok Pesantren",
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_templates');
    }
};
