<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Perizinan extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected $table = 'perizinan';

    protected $fillable = [
        'santri_id',
        'jenis_izin',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_kembali',
        'alasan',
        'status',
        'catatan_penolakan',
        'catatan_kembali',
        'disetujui_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'tanggal_kembali' => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('bukti_kembali')->singleFile();
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function notifikasiLog(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NotifikasiLog::class);
    }

    public function getJenisIzinLabelAttribute(): string
    {
        return match ($this->jenis_izin) {
            'pulang' => 'Izin Pulang Ke Rumah',
            'sakit' => 'Izin Berobat Out-Pondok',
            'acara_keluarga' => 'Acara Keluarga',
            default => ucfirst(str_replace('_', ' ', (string) $this->jenis_izin)),
        };
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['status', 'catatan_penolakan', 'catatan_kembali', 'tanggal_kembali', 'disetujui_oleh']);
    }
}
