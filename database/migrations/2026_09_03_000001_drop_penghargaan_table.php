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
        Schema::dropIfExists('penghargaan');

        // Bersihkan permission dan role-permission terkait modul penghargaan jika ada
        if (Schema::hasTable('permissions')) {
            $permissionIds = DB::table('permissions')
                ->where('name', 'like', '%Penghargaan%')
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
        Schema::create('penghargaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('pengurus_id')->nullable()->constrained('pengurus')->nullOnDelete();
            $table->enum('bidang', ['akademik', 'tahfidz', 'kedisiplinan', 'lomba']);
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->timestamps();
        });
    }
};
