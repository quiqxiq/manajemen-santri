<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifikasi_log', function (Blueprint $table) {
            $table->unsignedTinyInteger('attempts')->default(0)->after('status');
            $table->string('wa_message_id')->nullable()->after('sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('notifikasi_log', function (Blueprint $table) {
            $table->dropColumn(['attempts', 'wa_message_id']);
        });
    }
};
