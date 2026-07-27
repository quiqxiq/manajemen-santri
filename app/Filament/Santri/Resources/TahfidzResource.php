<?php

namespace App\Filament\Santri\Resources;

use App\Filament\Santri\Resources\TahfidzResource\Pages;
use App\Models\Tahfidz;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TahfidzResource extends Resource
{
    protected static ?string $model = Tahfidz::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bookmark-square';

    protected static ?string $pluralModelLabel = 'Catatan Hafalan Saya';

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $santriId = $user?->santri?->id;

        return parent::getEloquentQuery()
            ->where('santri_id', $santriId);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis Setoran')
                    ->badge(),
                Tables\Columns\TextColumn::make('surat')
                    ->label('Surat'),
                Tables\Columns\TextColumn::make('juz')
                    ->label('Juz'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Evaluasi')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'lulus' ? 'success' : 'danger'),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTahfidzs::route('/'),
        ];
    }
}
