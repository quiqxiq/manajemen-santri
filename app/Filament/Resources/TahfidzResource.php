<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahfidzResource\Pages;
use App\Models\Tahfidz;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class TahfidzResource extends Resource
{
    protected static ?string $model = Tahfidz::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bookmark-square';

    protected static string|UnitEnum|null $navigationGroup = 'Akademik & Tahfidz';

    protected static ?string $pluralModelLabel = 'Catatan Tahfidz';

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
                    ->label('Penguji / Ustadz')
                    ->relationship('pengurus', 'nama')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('jenis')
                    ->label('Jenis Setoran')
                    ->options([
                        'setoran' => 'Setoran Hafalan Baru',
                        'murojaah' => 'Murojaah (Pengulangan)',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('surat')
                    ->label('Nama Surat')
                    ->required()
                    ->placeholder('Misal: An-Naba'),
                Forms\Components\TextInput::make('juz')
                    ->label('Juz')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(30)
                    ->nullable(),
                Forms\Components\TextInput::make('ayat_dari')
                    ->label('Ayat Dari')
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('ayat_sampai')
                    ->label('Ayat Sampai')
                    ->numeric()
                    ->nullable(),
                Forms\Components\Select::make('status')
                    ->label('Hasil Evaluasi (R5)')
                    ->options([
                        'lulus' => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus (Perlu Murojaah)',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal Setoran')
                    ->default(now())
                    ->required(),
                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan Kualitas Bacaan')
                    ->rows(2)
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
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'setoran' ? 'primary' : 'secondary'),
                Tables\Columns\TextColumn::make('surat')
                    ->label('Surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('juz')
                    ->label('Juz')
                    ->default('-'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status (R5)')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'lulus' ? 'success' : 'danger')
                    ->formatStateUsing(fn (string $state): string => $state === 'lulus' ? 'Lulus' : 'Perlu Murojaah'),
                Tables\Columns\TextColumn::make('pengurus.nama')
                    ->label('Penguji')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')
                    ->options([
                        'setoran' => 'Setoran Hafalan Baru',
                        'murojaah' => 'Murojaah',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'lulus' => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus',
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
            'index' => Pages\ManageTahfidzs::route('/'),
        ];
    }
}
