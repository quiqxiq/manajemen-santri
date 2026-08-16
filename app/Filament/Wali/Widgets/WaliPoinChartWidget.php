<?php

namespace App\Filament\Wali\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Str;

class WaliPoinChartWidget extends ChartWidget
{
    protected ?string $heading = 'Akumulasi Poin Pelanggaran per Anak';

    protected ?string $maxHeight = '220px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $anakList = auth()->user()?->waliSantri?->santri ?? collect();

        return [
            'labels' => $anakList
                ->pluck('nama_lengkap')
                ->map(fn (string $nama): string => Str::limit($nama, 14))
                ->values()
                ->toArray(),
            'datasets' => [
                [
                    'label' => 'Poin Pelanggaran',
                    'data' => $anakList
                        ->map(fn ($santri): int => $santri->totalPoinPelanggaran())
                        ->values()
                        ->toArray(),
                    'backgroundColor' => '#10b981',
                    'borderRadius' => 6,
                ],
            ],
        ];
    }
}
