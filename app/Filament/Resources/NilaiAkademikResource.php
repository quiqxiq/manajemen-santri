<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiAkademikResource\Pages;
use App\Models\NilaiAkademik;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class NilaiAkademikResource extends Resource
{
    protected static ?string $model = NilaiAkademik::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    protected static string|UnitEnum|null $navigationGroup = 'Akademik & Tahfidz';

    protected static ?string $pluralModelLabel = 'Nilai Akademik';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('santri_id')
                    ->label('Santri')
                    ->relationship('santri', 'nama_lengkap')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('mata_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->relationship('mataPelajaran', 'nama_mapel')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('pengurus_id')
                    ->label('Ustadz / Penguji')
                    ->relationship('pengurus', 'nama')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options([
                        1 => 'Semester 1 (Ganjil)',
                        2 => 'Semester 2 (Genap)',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->default('2025/2026')
                    ->required(),
                Forms\Components\TextInput::make('nilai')
                    ->label('Nilai (0 - 100)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Catatan Evaluasi')
                    ->rows(2)
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('mataPelajaran.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->label('Tahun Ajaran'),
                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->sortable()
                    ->badge()
                    ->color(fn (float $state): string => match (true) {
                        $state >= 85 => 'success',
                        $state >= 70 => 'info',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('pengurus.nama')
                    ->label('Ustadz')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('mata_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->relationship('mataPelajaran', 'nama_mapel'),
                Tables\Filters\SelectFilter::make('semester')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
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
            'index' => Pages\ManageNilaiAkademiks::route('/'),
        ];
    }
}
