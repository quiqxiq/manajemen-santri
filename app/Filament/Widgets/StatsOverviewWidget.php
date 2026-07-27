<?php

namespace App\Filament\Widgets;

use App\Models\Pelanggaran;
use App\Models\Perizinan;
use App\Models\Santri;
use App\Models\Tagihan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSantri = Santri::where('status', 'aktif')->count();
        $totalTunggakan = Tagihan::whereIn('status', ['belum_lunas', 'sebagian'])->count();
        $pelanggaranKritis = Pelanggaran::where('status', 'perlu_tindakan')->count();
        $izinMenunggu = Perizinan::where('status', 'diajukan')->count();

        return [
            Stat::make('Santri Aktif', $totalSantri)
                ->description('Total santri terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Santri Menunggak', $totalTunggakan)
                ->description('Tagihan belum lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($totalTunggakan > 0 ? 'warning' : 'success'),
            Stat::make('Eskalasi Pelanggaran', $pelanggaranKritis)
                ->description('Status perlu tindakan pengasuh')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($pelanggaranKritis > 0 ? 'danger' : 'success'),
            Stat::make('Izin Menunggu Verifikasi', $izinMenunggu)
                ->description('Pengajuan izin keluar pondok')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('info'),
        ];
    }
}
