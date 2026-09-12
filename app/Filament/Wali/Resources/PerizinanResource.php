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
                    ->default(fn (): ?int => auth()->user()?->waliSantri?->santri->count() === 1
                        ? auth()->user()?->waliSantri?->santri->first()?->id
                        : null)
                    ->required()
                    ->searchable()
                    ->live()
                    ->helperText(function ($state): ?string {
                        if (! $state) {
                            return null;
                        }
                        $santri = \App\Models\Santri::find($state);
                        if ($santri && $santri->memilikiTunggakan()) {
                            $total = (float) $santri->tagihan()
                                ->whereIn('status', ['belum_lunas', 'sebagian'])
                                ->get()
                                ->sum(fn ($t) => $t->sisaTagihan());

                            $formattedTotal = 'Rp ' . number_format($total, 0, ',', '.');

                            return "⚠️ Perhatian: Santri ini memiliki tanggungan tagihan sebesar {$formattedTotal}. Pengajuan izin tidak dapat diproses sampai kewajiban pembayaran diselesaikan.";
                        }

                        return null;
                    }),
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('bukti_kembali')
                    ->label('Bukti Kembali')
                    ->collection('bukti_kembali')
                    ->square()
                    ->defaultImageUrl(null),
                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->label('Waktu Kembali')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
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
            ->recordActions([
                \Filament\Actions\Action::make('laporKembali')
                    ->label('Lapor Kembali')
                    ->icon('heroicon-o-camera')
                    ->color('success')
                    ->visible(fn (Perizinan $record): bool => $record->status === 'disetujui')
                    ->form([
                        Forms\Components\DateTimePicker::make('tanggal_kembali')
                            ->label('Waktu Kedatangan Kembali di Pondok')
                            ->default(now())
                            ->required(),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('bukti_kembali')
                            ->label('Foto Bukti Kedatangan / Kembali ke Pondok')
                            ->collection('bukti_kembali')
                            ->image()
                            ->required()
                            ->helperText('Unggah foto santri telah tiba di pondok (misal: di gerbang pondok / pos keamanan).'),
                        Forms\Components\Textarea::make('catatan_kembali')
                            ->label('Catatan Tambahan (Opsional)')
                            ->placeholder('Contoh: Santri tiba dengan sehat dan selamat didampingi orang tua.')
                            ->rows(2),
                    ])
                    ->action(function (Perizinan $record, array $data) {
                        $record->update([
                            'status' => 'selesai',
                            'tanggal_kembali' => $data['tanggal_kembali'],
                            'catatan_kembali' => $data['catatan_kembali'] ?? null,
                        ]);

                        app(\App\Services\PerizinanService::class)->kirimNotifikasiKembali($record);

                        \Filament\Notifications\Notification::make()
                            ->title('Laporan Kedatangan Berhasil')
                            ->body('Terima kasih, laporan kedatangan santri telah dicatat dan diteruskan ke keamanan pondok.')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\Action::make('lihatBukti')
                    ->label('Bukti')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->visible(fn (Perizinan $record): bool => $record->status === 'selesai' && $record->hasMedia('bukti_kembali'))
                    ->modalHeading('Foto Bukti Santri Kembali')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (Perizinan $record) => view('filament.wali.components.modal-lihat-bukti', [
                        'perizinan' => $record,
                    ])),
            ])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePerizinans::route('/'),
        ];
    }
}
