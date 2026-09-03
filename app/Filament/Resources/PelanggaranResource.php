<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelanggaranResource\Pages;
use App\Models\KategoriPelanggaran;
use App\Models\Pelanggaran;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class PelanggaranResource extends Resource
{
    protected static ?string $model = Pelanggaran::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static string|UnitEnum|null $navigationGroup = 'Kedisiplinan';

    protected static ?string $pluralModelLabel = 'Catatan Pelanggaran';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('santri_id')
                    ->label('Santri')
                    ->relationship('santri', 'nama_lengkap')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('kategori_pelanggaran_id')
                    ->label('Kategori Pelanggaran')
                    ->relationship('kategoriPelanggaran', 'nama_kategori')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            $kat = KategoriPelanggaran::find($state);
                            if ($kat) {
                                $set('poin', $kat->poin);
                            }
                        }
                    })
                    ->searchable(),
                Forms\Components\Select::make('pengurus_id')
                    ->label('Pengurus Pencatat')
                    ->relationship('pengurus', 'nama')
                    ->required()
                    ->searchable(),
                // Poin otomatis diambil dari kategori yang dipilih, tidak perlu diisi manual.
                Forms\Components\Hidden::make('poin'),
                Forms\Components\DatePicker::make('tanggal_kejadian')
                    ->label('Tanggal Kejadian')
                    ->default(now())
                    ->required(),
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Kejadian')
                    ->required()
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('kategoriPelanggaran.nama_kategori')
                    ->label('Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('poin')
                    ->label('Poin')
                    ->badge()
                    ->color('danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status (R2/R3)')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'perlu_tindakan' => 'danger',
                        'peringatan' => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'perlu_tindakan' => 'Perlu Tindakan Pengasuh',
                        'peringatan' => 'Peringatan Wali',
                        default => 'Normal',
                    }),
                Tables\Columns\TextColumn::make('pengurus.nama')
                    ->label('Pencatat')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'normal' => 'Normal',
                        'peringatan' => 'Peringatan Wali',
                        'perlu_tindakan' => 'Perlu Tindakan Pengasuh',
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
            'index' => Pages\ManagePelanggarans::route('/'),
        ];
    }
}
