<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('kedisiplinan.poin_peringatan', 50);
        $this->migrator->add('kedisiplinan.poin_kritis', 100);
    }
};
