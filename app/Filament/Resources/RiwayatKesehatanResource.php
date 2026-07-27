<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiwayatKesehatanResource\Pages;
use App\Models\RiwayatKesehatan;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class RiwayatKesehatanResource extends Resource
{
    protected static ?string $model = RiwayatKesehatan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static string|UnitEnum|null $navigationGroup = 'Kesehatan Santri';

    protected static ?string $pluralModelLabel = 'Riwayat Kesehatan';

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
                    ->label('Pengurus Kesehatan')
                    ->relationship('pengurus', 'nama')
                    ->required()
                    ->searchable(),
                Forms\Components\DatePicker::make('tanggal_kejadian')
                    ->label('Tanggal Kejadian')
                    ->default(now())
                    ->required(),
                Forms\Components\TextInput::make('suhu_tubuh')
                    ->label('Suhu Tubuh (°C)')
                    ->numeric()
                    ->step(0.1)
                    ->nullable(),
                Forms\Components\Textarea::make('keluhan')
                    ->label('Keluhan Utama')
                    ->required(),
                Forms\Components\Textarea::make('diagnosis_sementara')
                    ->label('Diagnosis Sementara')
                    ->nullable(),
                Forms\Components\Select::make('tindakan')
                    ->label('Tindakan Penanganan')
                    ->options([
                        'istirahat_kamar' => 'Istirahat di Kamar / UKS',
                        'pemberian_obat' => 'Pemberian Obat',
                        'mini_puskesmas' => 'Diperiksa Mini Puskesmas',
                        'rujuk_rs' => 'Dirujuk ke Rumah Sakit',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('tujuan_rujukan')
                    ->label('Rumah Sakit Tujuan Rujukan')
                    ->nullable(),
                Forms\Components\Select::make('status')
                    ->label('Status Penanganan')
                    ->options([
                        'dalam_perawatan' => 'Dalam Perawatan',
                        'dirujuk' => 'Dirujuk ke RS',
                        'selesai' => 'Sembuh / Selesai',
                    ])
                    ->default('dalam_perawatan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_kejadian')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Santri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keluhan')
                    ->label('Keluhan')
                    ->limit(40),
                Tables\Columns\TextColumn::make('suhu_tubuh')
                    ->label('Suhu (°C)')
                    ->default('-'),
                Tables\Columns\TextColumn::make('tindakan')
                    ->label('Tindakan')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai' => 'success',
                        'dirujuk' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'dalam_perawatan' => 'Dalam Perawatan',
                        'dirujuk' => 'Dirujuk',
                        'selesai' => 'Selesai',
                    ]),
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
            'index' => Pages\ManageRiwayatKesehatans::route('/'),
        ];
    }
}
