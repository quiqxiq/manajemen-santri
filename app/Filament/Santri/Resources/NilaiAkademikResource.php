<?php

namespace App\Filament\Santri\Resources;

use App\Filament\Santri\Resources\NilaiAkademikResource\Pages;
use App\Models\NilaiAkademik;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NilaiAkademikResource extends Resource
{
    protected static ?string $model = NilaiAkademik::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $pluralModelLabel = 'Nilai Akademik Saya';

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
                Tables\Columns\TextColumn::make('mataPelajaran.nama_mapel')
                    ->label('Mata Pelajaran'),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester'),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->label('Tahun Ajaran'),
                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->badge(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Catatan Ustadz'),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNilaiAkademiks::route('/'),
        ];
    }
}
