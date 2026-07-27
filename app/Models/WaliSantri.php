<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WaliSantri extends Model
{
    use HasFactory;

    protected $table = 'wali_santri';

    protected $fillable = [
        'user_id',
        'nama',
        'no_hp',
        'pekerjaan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function santri(): BelongsToMany
    {
        return $this->belongsToMany(Santri::class, 'santri_wali')
            ->withPivot(['hubungan', 'is_penanggung_jawab_utama'])
            ->withTimestamps();
    }

    public function notifikasiLog(): HasMany
    {
        return $this->hasMany(NotifikasiLog::class);
    }
}
