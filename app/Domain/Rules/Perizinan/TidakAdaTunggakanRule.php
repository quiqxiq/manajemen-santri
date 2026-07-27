<?php

namespace App\Domain\Rules\Perizinan;

use App\Domain\Rules\Contracts\BusinessRule;
use App\Models\Santri;

final class TidakAdaTunggakanRule implements BusinessRule
{
    public function passes(mixed $subject): bool
    {
        if (! $subject instanceof Santri) {
            return false;
        }

        return ! $subject->memilikiTunggakan();
    }

    public function message(): string
    {
        return 'Santri masih memiliki tunggakan pembayaran, pengajuan izin ditolak.';
    }
}
