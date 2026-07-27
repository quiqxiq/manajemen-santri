<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Tagihan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tagihan';

    protected $fillable = [
        'santri_id',
        'jenis',
        'bulan',
        'tahun',
        'nominal',
        'status',
        'jatuh_tempo',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'bulan' => 'integer',
            'tahun' => 'integer',
            'jatuh_tempo' => 'date',
        ];
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function totalDibayar(): float
    {
        return (float) $this->pembayaran()->sum('jumlah_bayar');
    }

    public function sisaTagihan(): float
    {
        return max(0, (float) $this->nominal - $this->totalDibayar());
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['status', 'nominal']);
    }
}
