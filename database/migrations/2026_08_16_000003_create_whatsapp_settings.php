<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('whatsapp.session_id', 'main');
        $this->migrator->add('whatsapp.template_pelanggaran', 'pelanggaran_peringatan');
    }
};
