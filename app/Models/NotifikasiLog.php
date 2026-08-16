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
}
