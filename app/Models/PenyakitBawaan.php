<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyakitBawaan extends Model
{
    use HasFactory;

    protected $table = 'penyakit_bawaan';

    protected $fillable = [
        'santri_id',
        'nama_penyakit',
        'keterangan',
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }
}
