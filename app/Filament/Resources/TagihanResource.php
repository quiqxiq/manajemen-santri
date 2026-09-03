<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanResource\Pages;
use App\Models\Tagihan;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?string $pluralModelLabel = 'Tagihan Santri';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('santri_id')
                    ->label('Santri')
                    ->relationship('santri', 'nama_lengkap')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('jenis')
                    ->label('Jenis Tagihan')
                    ->options([
                        'spp' => 'SPP Bulanan',
                        'daftar_ulang' => 'Daftar Ulang / Tahunan',
                        'lainnya' => 'Lainnya (Kitab/Kegiatan)',
                    ])
                    ->required(),
                Forms\Components\Select::make('bulan')
                    ->label('Bulan')
                    ->options([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ])
                    ->nullable(),
                Forms\Components\TextInput::make('tahun')
                    ->label('Tahun')
                    ->numeric()
                    ->default(date('Y'))
                    ->required(),
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal Tagihan (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status Pembayaran (R7)')
                    ->options([
                        'belum_lunas' => 'Belum Lunas',
                        'sebagian' => 'Sebagian',
                        'lunas' => 'Lunas',
                    ])
                    ->default('belum_lunas')
                    ->required(),
                Forms\Components\DatePicker::make('jatuh_tempo')
                    ->label('Tanggal Jatuh Tempo')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Santri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis Tagihan')
                    ->badge(),
                Tables\Columns\TextColumn::make('periode')
                    ->label('Periode')
                    ->state(fn (Tagihan $record): string => $record->bulan ? "Bulan {$record->bulan}/{$record->tahun}" : "Tahun {$record->tahun}"),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status (R7)')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lunas' => 'success',
                        'sebagian' => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('jatuh_tempo')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'belum_lunas' => 'Belum Lunas',
                        'sebagian' => 'Sebagian',
                        'lunas' => 'Lunas',
                    ]),
                Tables\Filters\SelectFilter::make('jenis')
                    ->options([
                        'spp' => 'SPP',
                        'daftar_ulang' => 'Daftar Ulang',
                        'lainnya' => 'Lainnya',
                    ]),
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
            'index' => Pages\ManageTagihans::route('/'),
        ];
    }
}
