<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerizinanResource\Pages;
use App\Models\Perizinan;
use App\Models\Santri;
use App\Services\PerizinanService;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PerizinanResource extends Resource
{
    protected static ?string $model = Perizinan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paper-airplane';

    protected static string|UnitEnum|null $navigationGroup = 'Perizinan Santri';

    protected static ?string $pluralModelLabel = 'Pengajuan Perizinan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('santri_id')
                    ->label('Santri')
                    ->relationship('santri', 'nama_lengkap')
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
                    ->required(),
                Forms\Components\TimePicker::make('jam_kembali_rencana')
                    ->label('Jam Batas Kepulangan (WIB)')
                    ->default('17:00:00')
                    ->seconds(false)
                    ->helperText('Batas jam kepulangan santri ke pondok pada tanggal selesai.'),
                Forms\Components\Textarea::make('alasan')
                    ->label('Alasan Perizinan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('dokumen_perizinan')
                    ->label('Dokumen Pendukung Pengajuan Izin')
                    ->collection('dokumen_perizinan')
                    ->disk('public')
                    ->visibility('public')
                    ->multiple()
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->maxFiles(5)
                    ->maxSize(5120)
                    ->helperText('Surat keterangan dokter, undangan keluarga, surat permohonan dll (Gambar / PDF maks 5MB).')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('Status Persetujuan')
                    ->options([
                        'diajukan' => 'Diajukan',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai / Kembali',
                    ])
                    ->default('diajukan')
                    ->required(),
                Forms\Components\Textarea::make('catatan_penolakan')
                    ->label('Alasan Penolakan (Bila Ditolak)')
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('tanggal_kembali')
                    ->label('Waktu Kedatangan Kembali')
                    ->nullable(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('bukti_kembali')
                    ->label('Foto & Dokumen Bukti Santri Kembali')
                    ->collection('bukti_kembali')
                    ->disk('public')
                    ->visibility('public')
                    ->multiple()
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->maxFiles(5)
                    ->maxSize(5120)
                    ->helperText('Foto kedatangan di gerbang/pos pondok atau dokumen kepulangan lainnya.')
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('catatan_kembali')
                    ->label('Catatan Kedatangan')
                    ->nullable()
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
                Tables\Columns\TextColumn::make('jenis_izin')
                    ->label('Jenis Izin')
                    ->badge(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Batas Kepulangan')
                    ->date('d/m/Y')
                    ->description(fn (Perizinan $record): string => ($record->jam_kembali_rencana ? substr($record->jam_kembali_rencana, 0, 5) . ' WIB' : '17:00 WIB') . ' • ' . $record->sisa_waktu_label)
                    ->sortable(),
                Tables\Columns\TextColumn::make('posisi_santri')
                    ->label('Posisi Santri')
                    ->badge()
                    ->state(fn (Perizinan $record): string => $record->posisi_santri_label)
                    ->color(fn (Perizinan $record): string => $record->posisi_santri_color)
                    ->icon(fn (Perizinan $record): string => match ($record->posisi_santri) {
                        'sedang_pulang' => 'heroicon-m-arrow-left-on-rectangle',
                        'sudah_kembali' => 'heroicon-m-check-circle',
                        'diajukan' => 'heroicon-m-clock',
                        default => 'heroicon-m-x-circle',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Persetujuan')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'disetujui' => 'Disetujui (Sedang Pulang)',
                        'selesai' => 'Selesai (Sudah Kembali)',
                        'diajukan' => 'Menunggu Izin',
                        'ditolak' => 'Ditolak',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'disetujui' => 'danger',  // Merah saat sedang pulang
                        'selesai' => 'success',   // Hijau saat sudah kembali
                        'ditolak' => 'gray',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('status_kepulangan')
                    ->label('Status Kepulangan')
                    ->badge()
                    ->state(fn (Perizinan $record): string => $record->status_kepulangan_label)
                    ->color(fn (Perizinan $record): string => $record->status_kepulangan_color),
                Tables\Columns\TextColumn::make('santri_tunggakan')
                    ->label('Status Tunggakan (R1)')
                    ->state(fn (Perizinan $record): string => (
                        $record->santri?->tagihan?->where(fn ($t) => in_array($t->status, ['belum_lunas', 'sebagian']))->count() ?? 0
                    ) > 0 ? 'Ada Tunggakan' : 'Lunas')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Ada Tunggakan' ? 'danger' : 'success'),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('bukti_kembali')
                    ->label('Bukti Kembali')
                    ->collection('bukti_kembali')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(null),
                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->label('Tgl Kembali')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->modifyQueryUsing(fn ($query) => $query->with(['santri.tagihan', 'santri.kamar', 'media', 'disetujuiOleh']))
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'diajukan' => 'Diajukan',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ]),
                Tables\Filters\SelectFilter::make('status_kepulangan')
                    ->label('Status Keterlambatan')
                    ->options([
                        'hampir_habis' => 'Hampir Habis (H-1 / Hari Ini)',
                        'terlambat' => 'Terlambat Kembali',
                        'aktif' => 'Izin Berjalan (Aktif)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (! empty($data['value'])) {
                            if ($data['value'] === 'terlambat') {
                                $query->where('status', 'disetujui')
                                    ->whereNull('tanggal_kembali')
                                    ->whereRaw("CONCAT(tanggal_selesai, ' ', COALESCE(jam_kembali_rencana, '17:00:00')) < ?", [now()]);
                            } elseif ($data['value'] === 'hampir_habis') {
                                $query->where('status', 'disetujui')
                                    ->whereNull('tanggal_kembali')
                                    ->whereRaw("CONCAT(tanggal_selesai, ' ', COALESCE(jam_kembali_rencana, '17:00:00')) >= ?", [now()])
                                    ->whereRaw("CONCAT(tanggal_selesai, ' ', COALESCE(jam_kembali_rencana, '17:00:00')) <= ?", [now()->addHours(24)]);
                            } elseif ($data['value'] === 'aktif') {
                                $query->where('status', 'disetujui')
                                    ->whereNull('tanggal_kembali');
                            }
                        }
                    }),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('setujui')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Perizinan $record) {
                        $reason = app(PerizinanService::class)->checkCanApply($record->santri);
                        if ($reason) {
                            $record->update([
                                'status' => 'ditolak',
                                'catatan_penolakan' => "Ditolak Otomatis (R1): {$reason}",
                            ]);
                            app(PerizinanService::class)->kirimNotifikasiDitolak($record);
                            Notification::make()
                                ->title('Pengajuan Ditolak Otomatis (R1)')
                                ->body($reason)
                                ->danger()
                                ->send();
                            return;
                        }

                        $record->update([
                            'status' => 'disetujui',
                            'disetujui_oleh' => auth()->id(),
                        ]);

                        app(PerizinanService::class)->kirimNotifikasiDisetujui($record);

                        Notification::make()
                            ->title('Perizinan Disetujui')
                            ->body('Persetujuan berhasil disimpan dan notifikasi telah dikirimkan ke wali santri.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Perizinan $record) => $record->status === 'diajukan'),
                \Filament\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('catatan_penolakan')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (Perizinan $record, array $data) {
                        $record->update([
                            'status' => 'ditolak',
                            'catatan_penolakan' => $data['catatan_penolakan'],
                        ]);

                        app(PerizinanService::class)->kirimNotifikasiDitolak($record);

                        Notification::make()
                            ->title('Perizinan Ditolak')
                            ->warning()
                            ->send();
                    })
                    ->visible(fn (Perizinan $record) => $record->status === 'diajukan'),
                \Filament\Actions\Action::make('konfirmasiKembali')
                    ->label('Konfirmasi Kembali')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Perizinan $record) => $record->status === 'disetujui')
                    ->form([
                        Forms\Components\DateTimePicker::make('tanggal_kembali')
                            ->label('Waktu Kedatangan Santri')
                            ->default(now())
                            ->required(),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('bukti_kembali')
                            ->label('Foto & Dokumen Bukti Kedatangan (Opsional oleh Petugas)')
                            ->collection('bukti_kembali')
                            ->disk('public')
                            ->visibility('public')
                            ->multiple()
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->maxFiles(5)
                            ->maxSize(5120)
                            ->nullable(),
                        Forms\Components\Textarea::make('catatan_kembali')
                            ->label('Catatan Petugas Pos Keamanan')
                            ->placeholder('Contoh: Santri telah kembali dan diperiksa oleh pos keamanan.')
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

                        Notification::make()
                            ->title('Perizinan Diselesaikan')
                            ->body('Status perizinan telah diperbarui menjadi Selesai / Kembali.')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\Action::make('ingatkanWali')
                    ->label('Ingatkan WA')
                    ->icon('heroicon-o-bell-alert')
                    ->color('warning')
                    ->visible(fn (Perizinan $record): bool => $record->status === 'disetujui' && is_null($record->tanggal_kembali))
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Pengingat Batas Waktu ke Wali')
                    ->modalDescription('Kirim pesan WhatsApp pengingat batas kepulangan santri ke seluruh nomor wali yang terdaftar?')
                    ->action(function (Perizinan $record) {
                        app(PerizinanService::class)->kirimNotifikasiPengingatKembali($record);

                        Notification::make()
                            ->title('Pengingat Terkirim')
                            ->body('Pesan WhatsApp pengingat kepulangan santri telah dikirimkan ke wali santri.')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\Action::make('lihatDokumen')
                    ->label('Lihat Berkas')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->color('gray')
                    ->modalHeading('Detail & Berkas Perizinan Santri')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('2xl')
                    ->modalContent(fn (Perizinan $record) => view('filament.wali.components.modal-lihat-bukti', [
                        'perizinan' => $record,
                    ])),
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
                \Filament\Actions\EditAction::make()
                    ->after(function (Perizinan $record) {
                        if ($record->wasChanged('status')) {
                            if ($record->status === 'disetujui') {
                                app(PerizinanService::class)->kirimNotifikasiDisetujui($record);
                            } elseif ($record->status === 'ditolak') {
                                app(PerizinanService::class)->kirimNotifikasiDitolak($record);
                            }
                        }
                    }),
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
            'index' => Pages\ManagePerizinans::route('/'),
        ];
    }
}
