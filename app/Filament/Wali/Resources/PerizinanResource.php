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
                Forms\Components\TimePicker::make('jam_kembali_rencana')
                    ->label('Rencana Jam Kembali ke Pondok (WIB)')
                    ->default('17:00:00')
                    ->seconds(false)
                    ->helperText('Perkiraan santri tiba di pondok (misal: 17:00 / sebelum Maghrib).')
                    ->required(),
                Forms\Components\Textarea::make('alasan')
                    ->label('Alasan Perizinan')
                    ->placeholder('Jelaskan keperluan izin anak Anda…')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('dokumen_perizinan')
                    ->label('Dokumen / Berkas Pendukung Izin')
                    ->collection('dokumen_perizinan')
                    ->disk('public')
                    ->visibility('public')
                    ->multiple()
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->maxFiles(5)
                    ->maxSize(5120)
                    ->helperText('Unggah surat keterangan sakit/dokter, undangan acara keluarga, dll (Gambar / PDF maks 5MB, opsional).')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Nama Anak'),
                Tables\Columns\TextColumn::make('posisi_santri')
                    ->label('Posisi Santri')
                    ->badge()
                    ->state(fn (Perizinan $record): string => $record->posisi_santri_label)
                    ->color(fn (Perizinan $record): string => $record->posisi_santri_color),
                Tables\Columns\TextColumn::make('jenis_izin')
                    ->label('Jenis Izin')
                    ->badge(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Batas Kepulangan')
                    ->date('d/m/Y')
                    ->description(fn (Perizinan $record): string => ($record->jam_kembali_rencana ? substr($record->jam_kembali_rencana, 0, 5) . ' WIB' : '17:00 WIB') . ' • ' . $record->sisa_waktu_label),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Persetujuan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'disetujui' => 'danger',
                        'selesai' => 'success',
                        'ditolak' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('status_kepulangan')
                    ->label('Status Kepulangan')
                    ->badge()
                    ->state(fn (Perizinan $record): string => $record->status_kepulangan_label)
                    ->color(fn (Perizinan $record): string => $record->status_kepulangan_color),
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
            ->modifyQueryUsing(fn ($query) => $query->with(['santri', 'media', 'disetujuiOleh']))
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
                            ->label('Foto Bukti Kedatangan & Dokumen Pendukung Kembali')
                            ->collection('bukti_kembali')
                            ->disk('public')
                            ->visibility('public')
                            ->multiple()
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->maxFiles(5)
                            ->maxSize(5120)
                            ->required()
                            ->helperText('Unggah foto santri telah tiba di pondok (misal: di gerbang pondok / pos keamanan) atau dokumen keterangan sehat.'),
                        Forms\Components\Textarea::make('catatan_kembali')
                            ->label('Catatan Tambahan (Opsional)')
                            ->placeholder('Contoh: Santri tiba dengan sehat dan selamat didampingi orang tua.')
                            ->rows(2),
                    ])
                    ->action(function (Perizinan $record, array $data, $schema = null) {
                        $record->update([
                            'status' => 'selesai',
                            'tanggal_kembali' => $data['tanggal_kembali'],
                            'catatan_kembali' => $data['catatan_kembali'] ?? null,
                        ]);

                        if ($schema && method_exists($schema, 'model')) {
                            $schema->model($record)->saveRelationships();
                        }

                        app(\App\Services\PerizinanService::class)->kirimNotifikasiKembali($record);

                        \Filament\Notifications\Notification::make()
                            ->title('Laporan Kedatangan Berhasil')
                            ->body('Terima kasih, laporan kedatangan santri telah dicatat dan diteruskan ke pos keamanan pondok.')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\Action::make('lihatDokumen')
                    ->label('Detail & Berkas')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->color('gray')
                    ->modalHeading('Detail & Berkas Perizinan')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('2xl')
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
