<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?string $pluralModelLabel = 'Catatan Pembayaran';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('tagihan_id')
                    ->label('Tagihan Santri')
                    ->relationship('tagihan', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Tagihan $record) => "Tagihan #{$record->id} - {$record->santri->nama_lengkap} ({$record->jenis}) Rp" . number_format((float)$record->nominal))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            $tagihan = Tagihan::find($state);
                            if ($tagihan) {
                                $set('santri_id', $tagihan->santri_id);
                                $set('jumlah_bayar', $tagihan->sisaTagihan());
                            }
                        }
                    })
                    ->searchable(),
                Forms\Components\Select::make('santri_id')
                    ->label('Santri')
                    ->relationship('santri', 'nama_lengkap')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('jumlah_bayar')
                    ->label('Jumlah Bayar (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_bayar')
                    ->label('Tanggal Pembayaran')
                    ->default(now())
                    ->required(),
                Forms\Components\Select::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->options([
                        'tunai' => 'Tunai',
                        'transfer' => 'Transfer Bank',
                        'qris' => 'QRIS',
                    ])
                    ->default('tunai')
                    ->required(),
                Forms\Components\Hidden::make('admin_id')
                    ->default(fn () => auth()->id() ?? 1),
                Forms\Components\SpatieMediaLibraryFileUpload::make('bukti_pembayaran')
                    ->label('Upload Bukti Transfer / Resi')
                    ->collection('bukti_pembayaran')
                    ->image()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Santri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tagihan.jenis')
                    ->label('Jenis Tagihan')
                    ->badge(),
                Tables\Columns\TextColumn::make('jumlah_bayar')
                    ->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->badge(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('bukti_pembayaran')
                    ->label('Bukti')
                    ->collection('bukti_pembayaran'),
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
            'index' => Pages\ManagePembayarans::route('/'),
        ];
    }
}
