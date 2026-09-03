<?php

namespace App\Services;

use App\Models\Santri;
use App\Models\Tahfidz;

class TahfidzService
{
    public function catatSetoran(Santri $santri, array $data): Tahfidz
    {
        $status = $data['status'] ?? 'lulus';

        return $santri->tahfidz()->create(array_merge($data, [
            'status' => $status,
        ]));
    }
}

