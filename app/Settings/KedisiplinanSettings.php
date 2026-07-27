<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class KedisiplinanSettings extends Settings
{
    public int $poin_peringatan;
    public int $poin_kritis;

    public static function group(): string
    {
        return 'kedisiplinan';
    }
}
