<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'nama_kamar',
        'kapasitas',
        'pengurus_pembina_id',
        'keterangan',
    ];

    public function pengurusPembina(): BelongsTo
    {
        return $this->belongsTo(Pengurus::class, 'pengurus_pembina_id');
    }

    public function santri(): HasMany
    {
        return $this->hasMany(Santri::class);
    }
}
