<?php

namespace App\Filament\Wali\Resources;

use App\Filament\Wali\Resources\TagihanResource\Pages;
use App\Models\Tagihan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $pluralModelLabel = 'Tagihan & Pembayaran';

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $waliId = $user?->waliSantri?->id;

        return parent::getEloquentQuery()
            ->whereHas('santri.waliSantri', function (Builder $query) use ($waliId) {
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
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Nama Anak'),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis Tagihan')
                    ->badge(),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal Tagihan')
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
                    ->date(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTagihans::route('/'),
        ];
    }
}
