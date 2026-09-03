<?php

namespace App\Filament\Wali\Resources;

use App\Filament\Wali\Resources\TahfidzResource\Pages;
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

    protected static ?string $pluralModelLabel = 'Perkembangan Hafalan Anak';

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
                Tables\Columns\TextColumn::make('tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Nama Anak'),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis Setoran')
                    ->badge(),
                Tables\Columns\TextColumn::make('surat')
                    ->label('Surat'),
                Tables\Columns\TextColumn::make('juz')
                    ->label('Juz'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'lulus' ? 'success' : 'danger'),
            ])
            ->defaultSort('created_at', 'desc')
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
