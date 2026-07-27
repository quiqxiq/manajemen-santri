<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Pelanggaran extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'pelanggaran';

    protected $fillable = [
        'santri_id',
        'kategori_pelanggaran_id',
        'pengurus_id',
        'deskripsi',
        'tanggal_kejadian',
        'poin',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kejadian' => 'date',
            'poin' => 'integer',
        ];
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function kategoriPelanggaran(): BelongsTo
    {
        return $this->belongsTo(KategoriPelanggaran::class);
    }

    public function pengurus(): BelongsTo
    {
        return $this->belongsTo(Pengurus::class);
    }

    public function notifikasiLog(): HasMany
    {
        return $this->hasMany(NotifikasiLog::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['santri_id', 'poin', 'status']);
    }
}
