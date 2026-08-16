<?php

namespace App\Filament\Wali\Widgets;

use App\Models\Tagihan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class WaliTagihanWidget extends BaseWidget
{
    protected static ?string $heading = 'Tagihan Anak';

    protected function getTableQuery(): Builder
    {
        $waliId = auth()->user()?->waliSantri?->id;

        return Tagihan::query()
            ->with('santri')
            ->whereHas('santri.waliSantri', fn (Builder $q) => $q->where('wali_santri.id', $waliId));
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Ananda')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'spp' => 'info',
                        'daftar_ulang' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('bulan')
                    ->label('Bulan')
                    ->formatStateUsing(fn (?int $state): string => $state ? \Carbon\Carbon::create()->month($state)->translatedFormat('F') : '-'),
                Tables\Columns\TextColumn::make('tahun')
                    ->label('Tahun'),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lunas' => 'success',
                        'sebagian' => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->placeholder('-'),
            ])
            ->defaultSort('jatuh_tempo', 'desc')
            ->paginated(false);
    }
}
