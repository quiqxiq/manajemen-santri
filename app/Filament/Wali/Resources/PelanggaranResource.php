<?php

namespace App\Filament\Wali\Resources;

use App\Filament\Wali\Resources\PelanggaranResource\Pages;
use App\Models\Pelanggaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PelanggaranResource extends Resource
{
    protected static ?string $model = Pelanggaran::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static ?string $pluralModelLabel = 'Catatan Pelanggaran Anak';

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
                Tables\Columns\TextColumn::make('tanggal_kejadian')
                    ->date(),
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Nama Anak'),
                Tables\Columns\TextColumn::make('kategoriPelanggaran.nama_kategori')
                    ->label('Kategori Pelanggaran'),
                Tables\Columns\TextColumn::make('poin')
                    ->label('Poin')
                    ->badge()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Keterangan Kejadian'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePelanggarans::route('/'),
        ];
    }
}
