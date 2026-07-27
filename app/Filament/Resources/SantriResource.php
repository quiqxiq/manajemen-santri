<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SantriResource\Pages;
use App\Models\Santri;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Santri';

    protected static ?string $pluralModelLabel = 'Data Santri';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Identitas Santri')
                    ->schema([
                        Forms\Components\TextInput::make('nis')
                            ->label('NIS')
                            ->default(fn () => 'SAN-' . date('Ym') . rand(100, 999))
                            ->required()
                            ->readOnly(),
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('asal_sekolah')
                            ->label('Asal Sekolah')
                            ->maxLength(255),
                        Forms\Components\Select::make('kamar_id')
                            ->label('Kamar / Asrama')
                            ->relationship('kamar', 'nama_kamar')
                            ->nullable()
                            ->searchable(),
                        Forms\Components\Select::make('status')
                            ->label('Status Santri')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Non-Aktif',
                                'lulus' => 'Lulus',
                                'keluar' => 'Keluar',
                            ])
                            ->default('aktif')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_masuk')
                            ->label('Tanggal Masuk')
                            ->default(now())
                            ->required(),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('foto_profil')
                            ->label('Foto Profil')
                            ->collection('foto_profil')
                            ->image()
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('foto_profil')
                    ->label('Foto')
                    ->collection('foto_profil')
                    ->circular(),
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Santri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kamar.nama_kamar')
                    ->label('Kamar')
                    ->default('-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'gray',
                        'lulus' => 'info',
                        'keluar' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('total_poin')
                    ->label('Poin Pelanggaran')
                    ->state(fn (Santri $record): int => $record->totalPoinPelanggaran())
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 100 => 'danger',
                        $state >= 50 => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('tunggakan')
                    ->label('Tunggakan')
                    ->state(fn (Santri $record): string => $record->memilikiTunggakan() ? 'Ada' : 'Lunas')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Ada' ? 'danger' : 'success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non-Aktif',
                        'lulus' => 'Lulus',
                        'keluar' => 'Keluar',
                    ]),
                Tables\Filters\SelectFilter::make('kamar_id')
                    ->label('Kamar')
                    ->relationship('kamar', 'nama_kamar'),
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
            'index' => Pages\ListSantris::route('/'),
            'create' => Pages\CreateSantri::route('/create'),
            'edit' => Pages\EditSantri::route('/{record}/edit'),
        ];
    }
}
