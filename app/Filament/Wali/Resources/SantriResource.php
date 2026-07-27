<?php

namespace App\Filament\Wali\Resources;

use App\Filament\Wali\Resources\SantriResource\Pages;
use App\Models\Santri;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $pluralModelLabel = 'Data Anak Asuh';

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $waliId = $user?->waliSantri?->id;

        return parent::getEloquentQuery()
            ->whereHas('waliSantri', function (Builder $query) use ($waliId) {
                $query->where('wali_santri.id', $waliId);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS'),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Anak'),
                Tables\Columns\TextColumn::make('kamar.nama_kamar')
                    ->label('Kamar'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                Tables\Columns\TextColumn::make('total_poin')
                    ->label('Akumulasi Poin Pelanggaran')
                    ->state(fn (Santri $record): int => $record->totalPoinPelanggaran())
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 100 => 'danger',
                        $state >= 50 => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('tunggakan')
                    ->label('Status Keuangan')
                    ->state(fn (Santri $record): string => $record->memilikiTunggakan() ? 'Ada Tunggakan' : 'Lunas')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Ada Tunggakan' ? 'danger' : 'success'),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSantris::route('/'),
        ];
    }
}
