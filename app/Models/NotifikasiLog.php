<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiLog extends Model
{
    use HasFactory;

    protected $table = 'notifikasi_log';

    protected $fillable = [
        'wali_santri_id',
        'pelanggaran_id',
        'tagihan_id',
        'perizinan_id',
        'pengurus_id',
        'no_hp_tujuan',
        'nama_tujuan',
        'channel',
        'pesan',
        'status',
        'attempts',
        'sent_at',
        'wa_message_id',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'attempts' => 'integer',
        ];
    }

    public function waliSantri(): BelongsTo
    {
        return $this->belongsTo(WaliSantri::class);
    }

    public function pelanggaran(): BelongsTo
    {
        return $this->belongsTo(Pelanggaran::class);
    }

    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function perizinan(): BelongsTo
    {
        return $this->belongsTo(Perizinan::class);
    }

    public function pengurus(): BelongsTo
    {
        return $this->belongsTo(Pengurus::class);
    }

    public function getNamaPenerimaAttribute(): string
    {
        return $this->nama_tujuan
            ?? $this->waliSantri?->nama
            ?? $this->pengurus?->nama
            ?? '-';
    }

    public function getNoHpPenerimaAttribute(): ?string
    {
        return $this->no_hp_tujuan
            ?? $this->waliSantri?->no_hp
            ?? $this->pengurus?->no_hp;
    }
}
