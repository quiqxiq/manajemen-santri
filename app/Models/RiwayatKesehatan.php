<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatKesehatan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kesehatan';

    protected $fillable = [
        'santri_id',
        'pengurus_id',
        'tanggal_kejadian',
        'keluhan',
        'suhu_tubuh',
        'diagnosis_sementara',
        'tindakan',
        'tujuan_rujukan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kejadian' => 'date',
            'suhu_tubuh' => 'decimal:1',
        ];
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function pengurus(): BelongsTo
    {
        return $this->belongsTo(Pengurus::class);
    }
}
