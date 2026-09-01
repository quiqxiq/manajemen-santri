<?php

namespace App\Filament\Wali\Widgets;

use App\Models\Tagihan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WaliOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $wali = $user?->waliSantri;

        if (! $wali) {
            return [];
        }

        $anakList = $wali->santri()
            ->withSum('pelanggaran', 'poin')
            ->withCount([
                'tagihan as tunggakan_count' => fn ($q) => $q->whereIn('status', ['belum_lunas', 'sebagian']),
            ])
            ->get();
        $totalAnak = $anakList->count();
        $totalPoin = (int) $anakList->sum('pelanggaran_sum_poin');
        $adaTunggakan = $anakList->contains(fn ($s) => ($s->tagihan_tunggakan_count ?? 0) > 0);

        $tagihanTerdekat = Tagihan::query()
            ->whereHas('santri.waliSantri', fn ($q) => $q->where('wali_santri.id', $wali->id))
            ->where('status', '!=', 'lunas')
            ->whereNotNull('jatuh_tempo')
            ->orderBy('jatuh_tempo')
            ->first();

        return [
            Stat::make('Anak Asuh', $totalAnak)
                ->description('Jumlah santri di bawah naungan')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Akumulasi Poin Pelanggaran', $totalPoin)
                ->description($totalPoin >= 50 ? 'Perlu perhatian lebih' : 'Dalam batas wajar')
                ->descriptionIcon('heroicon-m-shield-exclamation')
                ->color($totalPoin >= 50 ? 'danger' : 'success'),
            Stat::make('Tagihan Terdekat', $tagihanTerdekat
                ? 'Rp ' . number_format((float) $tagihanTerdekat->nominal, 0, ',', '.')
                : 'Tidak ada')
                ->description($tagihanTerdekat
                    ? 'Jatuh tempo ' . $tagihanTerdekat->jatuh_tempo->format('d M Y')
                    : 'Tidak ada tagihan tertunggak')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($tagihanTerdekat ? 'warning' : 'success'),
            Stat::make('Status Keuangan', $adaTunggakan ? 'Ada Tunggakan' : 'Lunas')
                ->description('Status pembayaran SPP/iuran')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color($adaTunggakan ? 'danger' : 'success'),
        ];
    }
}
