<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('penyakit_bawaan');
        Schema::dropIfExists('riwayat_kesehatan');

        // Bersihkan permission dan role-permission terkait modul penyakit & riwayat kesehatan jika ada
        if (Schema::hasTable('permissions')) {
            $permissionIds = DB::table('permissions')
                ->where('name', 'like', '%PenyakitBawaan%')
                ->orWhere('name', 'like', '%RiwayatKesehatan%')
                ->pluck('id');

            if ($permissionIds->isNotEmpty()) {
                if (Schema::hasTable('role_has_permissions')) {
                    DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
                }
                if (Schema::hasTable('model_has_permissions')) {
                    DB::table('model_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
                }
                DB::table('permissions')->whereIn('id', $permissionIds)->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('penyakit_bawaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->string('nama_penyakit');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('riwayat_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('pengurus_id')->nullable()->constrained('pengurus')->nullOnDelete();
            $table->date('tanggal_kejadian');
            $table->text('keluhan');
            $table->decimal('suhu_tubuh', 4, 1)->nullable();
            $table->string('diagnosis_sementara')->nullable();
            $table->enum('tindakan', ['istirahat_kamar', 'pemberian_obat', 'mini_puskesmas', 'rujuk_rs'])->default('istirahat_kamar');
            $table->enum('status', ['dalam_perawatan', 'selesai', 'dirujuk'])->default('dalam_perawatan');
            $table->text('catatan_medis')->nullable();
            $table->timestamps();
        });
    }
};
