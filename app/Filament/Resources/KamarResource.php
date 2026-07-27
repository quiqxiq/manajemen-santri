<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KamarResource\Pages;
use App\Models\Kamar;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class KamarResource extends Resource
{
    protected static ?string $model = Kamar::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home-modern';

    protected static string|UnitEnum|null $navigationGroup = 'Data Master';

    protected static ?string $pluralModelLabel = 'Data Kamar / Asrama';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('nama_kamar')
                    ->label('Nama Kamar / Gedung')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kapasitas')
                    ->label('Kapasitas Santri')
                    ->numeric()
                    ->default(10)
                    ->required(),
                Forms\Components\Select::make('pengurus_pembina_id')
                    ->label('Pengurus Pembina Kamar')
                    ->relationship('pembina', 'nama')
                    ->nullable()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kamar')
                    ->label('Nama Kamar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kapasitas')
                    ->label('Kapasitas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('santri_count')
                    ->label('Jumlah Santri')
                    ->counts('santri'),
                Tables\Columns\TextColumn::make('pembina.nama')
                    ->label('Pembina Kamar')
                    ->default('-'),
            ])
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
            'index' => Pages\ListKamars::route('/'),
            'create' => Pages\CreateKamar::route('/create'),
            'edit' => Pages\EditKamar::route('/{record}/edit'),
        ];
    }
}
