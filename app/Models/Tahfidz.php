<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tahfidz extends Model
{
    use HasFactory;

    protected $table = 'tahfidz';

    protected $fillable = [
        'santri_id',
        'pengurus_id',
        'jenis',
        'surat',
        'juz',
        'ayat_dari',
        'ayat_sampai',
        'status',
        'catatan',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'juz' => 'integer',
            'ayat_dari' => 'integer',
            'ayat_sampai' => 'integer',
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
