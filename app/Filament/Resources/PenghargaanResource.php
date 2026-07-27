<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenghargaanResource\Pages;
use App\Models\Penghargaan;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class PenghargaanResource extends Resource
{
    protected static ?string $model = Penghargaan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-trophy';

    protected static string|UnitEnum|null $navigationGroup = 'Kedisiplinan';

    protected static ?string $pluralModelLabel = 'Penghargaan Santri';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('santri_id')
                    ->label('Santri')
                    ->relationship('santri', 'nama_lengkap')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('pengurus_id')
                    ->label('Pengurus / Pengasuh')
                    ->relationship('pengurus', 'nama')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('bidang')
                    ->label('Bidang Penghargaan')
                    ->options([
                        'akademik' => 'Akademik',
                        'tahfidz' => 'Tahfidz Al-Qur\'an',
                        'kedisiplinan' => 'Kedisiplinan / Akhlaq',
                        'lomba' => 'Lomba / Kejuaraan',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->default(now())
                    ->required(),
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Prestasi')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Santri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bidang')
                    ->label('Bidang')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi Prestasi')
                    ->limit(50),
                Tables\Columns\TextColumn::make('pengurus.nama')
                    ->label('Pengasuh/Pencatat')
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ManagePenghargaans::route('/'),
        ];
    }
}
