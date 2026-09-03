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
                Forms\Components\Textarea::make('alasan')
                    ->label('Alasan Perizinan')
                    ->required()
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
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'disetujui' => 'success',
                        'selesai' => 'info',
                        'ditolak' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('santri_tunggakan')
                    ->label('Status Tunggakan (R1)')
                    ->state(fn (Perizinan $record): string => (
                        $record->santri?->tagihan?->where(fn ($t) => in_array($t->status, ['belum_lunas', 'sebagian']))->count() ?? 0
                    ) > 0 ? 'Ada Tunggakan' : 'Lunas')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Ada Tunggakan' ? 'danger' : 'success'),
            ])
            ->modifyQueryUsing(fn ($query) => $query->with(['santri.tagihan']))
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

                        Notification::make()
                            ->title('Perizinan Disetujui')
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
                        Notification::make()
                            ->title('Perizinan Ditolak')
                            ->warning()
                            ->send();
                    })
                    ->visible(fn (Perizinan $record) => $record->status === 'diajukan'),
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
            'index' => Pages\ManagePerizinans::route('/'),
        ];
    }
}
