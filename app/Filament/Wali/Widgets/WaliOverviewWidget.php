<?php

namespace App\Filament\Wali\Widgets;

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

        $anakList = $wali->santri;
        $totalAnak = $anakList->count();
        $totalPoin = $anakList->sum(fn ($s) => $s->totalPoinPelanggaran());
        $adaTunggakan = $anakList->contains(fn ($s) => $s->memilikiTunggakan());

        return [
            Stat::make('Anak Asuh Terdaftar', $totalAnak)
                ->description('Jumlah santri di bawah naungan')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make('Status Keuangan', $adaTunggakan ? 'Ada Tunggakan' : 'Lunas')
                ->description('Status pembayaran SPP/iuran')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($adaTunggakan ? 'danger' : 'success'),
            Stat::make('Akumulasi Poin Pelanggaran', $totalPoin)
                ->description('Total poin poin pelanggaran')
                ->descriptionIcon('heroicon-m-shield-exclamation')
                ->color($totalPoin >= 50 ? 'warning' : 'success'),
        ];
    }
}
