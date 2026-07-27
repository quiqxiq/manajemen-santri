<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Pengurus extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'pengurus';

    protected $fillable = [
        'user_id',
        'nama',
        'bagian',
        'no_hp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kamarBinaan(): HasMany
    {
        return $this->hasMany(Kamar::class, 'pengurus_pembina_id');
    }

    public function pelanggaranDicatat(): HasMany
    {
        return $this->hasMany(Pelanggaran::class);
    }

    public function tahfidzDicatat(): HasMany
    {
        return $this->hasMany(Tahfidz::class);
    }

    public function riwayatKesehatanDicatat(): HasMany
    {
        return $this->hasMany(RiwayatKesehatan::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['nama', 'bagian']);
    }
}
