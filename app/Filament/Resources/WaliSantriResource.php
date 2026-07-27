<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaliSantriResource\Pages;
use App\Models\WaliSantri;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class WaliSantriResource extends Resource
{
    protected static ?string $model = WaliSantri::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Santri';

    protected static ?string $pluralModelLabel = 'Data Wali Santri';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Akun User Login')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Wali')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_hp')
                    ->label('Nomor WhatsApp / HP')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->maxLength(255),
                Forms\Components\Select::make('santri')
                    ->label('Santri Anak Asuh')
                    ->relationship('santri', 'nama_lengkap')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Wali')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP / WA')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->default('-'),
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Anak Asuh')
                    ->badge()
                    ->separator(', '),
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
            'index' => Pages\ManageWaliSantris::route('/'),
        ];
    }
}
