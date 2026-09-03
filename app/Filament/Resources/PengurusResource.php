<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengurusResource\Pages;
use App\Models\Pengurus;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class PengurusResource extends Resource
{
    protected static ?string $model = Pengurus::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Pengguna';

    protected static ?string $pluralModelLabel = 'Data Pengurus';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Akun Login User')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('bagian')
                    ->label('Bagian Tugas')
                    ->options([
                        'tata_usaha' => 'Tata Usaha',
                        'keuangan' => 'Keuangan',
                        'keamanan' => 'Keamanan',
                        'akademik' => 'Akademik',
                        'tahfidz' => 'Tahfidz',
                        'kesehatan' => 'Kesehatan',
                        'pengasuhan' => 'Pengasuhan',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('no_hp')
                    ->label('Nomor WhatsApp / HP')
                    ->tel()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bagian')
                    ->label('Bagian')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP')
                    ->default('-'),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Username')
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
            'index' => Pages\ManagePengurus::route('/'),
        ];
    }
}
