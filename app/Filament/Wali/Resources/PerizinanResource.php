<?php

namespace App\Filament\Wali\Resources;

use App\Filament\Wali\Resources\PerizinanResource\Pages;
use App\Models\Perizinan;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PerizinanResource extends Resource
{
    protected static ?string $model = Perizinan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $pluralModelLabel = 'Pengajuan Perizinan';

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $waliId = $user?->waliSantri?->id;

        return parent::getEloquentQuery()
            ->whereHas('santri.waliSantri', function (Builder $query) use ($waliId) {
                $query->where('wali_santri.id', $waliId);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('santri_id')
                    ->label('Pilih Anak / Santri')
                    ->options(fn (): array => auth()->user()?->waliSantri?->santri
                        ->pluck('nama_lengkap', 'id')
                        ->all() ?? [])
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('jenis_izin')
                    ->label('Jenis Izin')
                    ->options([
                        'pulang' => 'Izin Pulang Ke Rumah',
                        'sakit' => 'Izin Berobat Out-Pondok',
                        'acara_keluarga' => 'Acara Keluarga',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->default(now())
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->default(now()->addDays(2))
                    ->required()
                    ->afterOrEqual('tanggal_mulai'),
                Forms\Components\Textarea::make('alasan')
                    ->label('Alasan Perizinan')
                    ->placeholder('Jelaskan keperluan izin anak Anda…')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Nama Anak'),
                Tables\Columns\TextColumn::make('jenis_izin')
                    ->label('Jenis Izin')
                    ->badge(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date(),
                Tables\Columns\TextColumn::make('alasan')
                    ->label('Alasan')
                    ->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Persetujuan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'disetujui' => 'success',
                        'selesai' => 'info',
                        'ditolak' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('catatan_penolakan')
                    ->label('Catatan Penolakan')
                    ->default('-'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'diajukan' => 'Diajukan',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ]),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePerizinans::route('/'),
        ];
    }
}
