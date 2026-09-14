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
        'jam_kembali_rencana',
        'tanggal_kembali',
        'alasan',
        'status',
        'catatan_penolakan',
        'catatan_kembali',
        'disetujui_oleh',
        'pengingat_kembali_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'tanggal_kembali' => 'datetime',
            'pengingat_kembali_sent_at' => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('dokumen_perizinan');
        $this->addMediaCollection('bukti_kembali');
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

    /**
     * Waktu datetime pasti batas kepulangan santri (tanggal_selesai + jam_kembali_rencana).
     */
    public function getTenggatWaktuAttribute(): ?\Carbon\Carbon
    {
        if (! $this->tanggal_selesai) {
            return null;
        }

        $jam = $this->jam_kembali_rencana ?: '17:00:00';

        return \Carbon\Carbon::parse($this->tanggal_selesai->format('Y-m-d') . ' ' . $jam);
    }

    /**
     * Status kepulangan santri berdasarkan tenggat waktu.
     */
    public function getStatusKepulanganAttribute(): string
    {
        if ($this->status === 'selesai') {
            $tenggat = $this->tenggat_waktu;
            if ($this->tanggal_kembali && $tenggat && $this->tanggal_kembali->gt($tenggat)) {
                return 'selesai_terlambat';
            }

            return 'selesai_tepat_waktu';
        }

        if ($this->status === 'disetujui') {
            $tenggat = $this->tenggat_waktu;
            if (! $tenggat) {
                return 'aktif';
            }

            if (now()->gt($tenggat)) {
                return 'terlambat';
            }

            if (now()->diffInHours($tenggat, false) <= 24) {
                return 'hampir_habis';
            }

            return 'aktif';
        }

        return $this->status;
    }

    /**
     * Label representasi visual untuk status kepulangan.
     */
    public function getStatusKepulanganLabelAttribute(): string
    {
        return match ($this->status_kepulangan) {
            'terlambat' => 'Terlambat Kembali',
            'hampir_habis' => 'Hampir Habis (H-1 / Hari Ini)',
            'aktif' => 'Izin Aktif / Menunggu Kembali',
            'selesai_tepat_waktu' => 'Kembali Tepat Waktu',
            'selesai_terlambat' => 'Kembali Terlambat',
            'diajukan' => 'Menunggu Persetujuan',
            'ditolak' => 'Ditolak',
            default => ucfirst((string) $this->status),
        };
    }

    /**
     * Deskripsi selisih sisa waktu atau keterlambatan kepulangan.
     */
    public function getSisaWaktuLabelAttribute(): string
    {
        $tenggat = $this->tenggat_waktu;
        if (! $tenggat) {
            return '-';
        }

        if ($this->status === 'selesai' && $this->tanggal_kembali) {
            if ($this->tanggal_kembali->gt($tenggat)) {
                return 'Terlambat ' . $tenggat->diffForHumans($this->tanggal_kembali, true);
            }

            return 'Tepat waktu (' . $this->tanggal_kembali->diffForHumans($tenggat, true) . ' lebih awal)';
        }

        if (now()->gt($tenggat)) {
            return 'Terlambat ' . $tenggat->diffForHumans(now(), true);
        }

        return 'Sisa ' . now()->diffForHumans($tenggat, true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly([
            'status',
            'catatan_penolakan',
            'catatan_kembali',
            'tanggal_kembali',
            'disetujui_oleh',
            'jam_kembali_rencana',
            'pengingat_kembali_sent_at',
        ]);
    }
}
