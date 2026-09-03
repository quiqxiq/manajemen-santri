<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriPelanggaranResource\Pages;
use App\Models\KategoriPelanggaran;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class KategoriPelanggaranResource extends Resource
{
    protected static ?string $model = KategoriPelanggaran::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string|UnitEnum|null $navigationGroup = 'Data Master';

    protected static ?string $pluralModelLabel = 'Kategori Pelanggaran';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('nama_kategori')
                    ->label('Nama Kategori Pelanggaran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('poin')
                    ->label('Bobot Poin')
                    ->numeric()
                    ->required()
                    ->minValue(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kategori')
                    ->label('Kategori Pelanggaran')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('poin')
                    ->label('Bobot Poin')
                    ->sortable()
                    ->badge()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKategoriPelanggarans::route('/'),
        ];
    }
}
