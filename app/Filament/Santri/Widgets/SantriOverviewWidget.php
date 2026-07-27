<?php

namespace App\Filament\Santri\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SantriOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $santri = $user?->santri;

        if (! $santri) {
            return [];
        }

        $totalHafalan = $santri->tahfidz()->where('status', 'lulus')->count();
        $totalPoin = $santri->totalPoinPelanggaran();
        $izinAktif = $santri->perizinan()->where('status', 'disetujui')->count();

        return [
            Stat::make('Setoran Hafalan Lulus', $totalHafalan)
                ->description('Jumlah setoran hafalan lulus')
                ->descriptionIcon('heroicon-m-bookmark-square')
                ->color('success'),
            Stat::make('Poin Pelanggaran', $totalPoin)
                ->description('Akumulasi poin kedisiplinan')
                ->descriptionIcon('heroicon-m-shield-exclamation')
                ->color($totalPoin >= 50 ? 'danger' : 'success'),
            Stat::make('Izin Aktif / Disetujui', $izinAktif)
                ->description('Perizinan keluar pondok')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('info'),
        ];
    }
}
