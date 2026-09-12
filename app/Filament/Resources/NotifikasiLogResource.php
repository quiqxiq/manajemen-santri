<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotifikasiLogResource\Pages;
use App\Jobs\KirimNotifikasiWhatsApp;
use App\Models\NotifikasiLog;
use BackedEnum;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use UnitEnum;

class NotifikasiLogResource extends Resource
{
    protected static ?string $model = NotifikasiLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static string|UnitEnum|null $navigationGroup = 'Sistem & Pengaturan';

    protected static ?string $pluralModelLabel = 'Log Notifikasi WA Bot';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Event')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_penerima')
                    ->label('Penerima')
                    ->state(fn (NotifikasiLog $record): string => $record->nama_penerima)
                    ->searchable(query: function ($query, string $search) {
                        $query->where('nama_tujuan', 'like', "%{$search}%")
                            ->orWhereHas('waliSantri', fn ($q) => $q->where('nama', 'like', "%{$search}%"))
                            ->orWhereHas('pengurus', fn ($q) => $q->where('nama', 'like', "%{$search}%"));
                    }),
                Tables\Columns\TextColumn::make('no_hp_penerima')
                    ->label('No. WA')
                    ->state(fn (NotifikasiLog $record): ?string => $record->no_hp_penerima)
                    ->searchable(query: function ($query, string $search) {
                        $query->where('no_hp_tujuan', 'like', "%{$search}%")
                            ->orWhereHas('waliSantri', fn ($q) => $q->where('no_hp', 'like', "%{$search}%"))
                            ->orWhereHas('pengurus', fn ($q) => $q->where('no_hp', 'like', "%{$search}%"));
                    }),
                Tables\Columns\TextColumn::make('pesan')
                    ->label('Pesan')
                    ->limit(60),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('attempts')
                    ->label('Percobaan')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Terkirim')
                    ->dateTime()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('wa_message_id')
                    ->label('ID Pesan WA')
                    ->limit(20)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('error_message')
                    ->label('Pesan Error')
                    ->limit(40)
                    ->placeholder('-'),
            ])
            ->modifyQueryUsing(fn ($query) => $query->with(['waliSantri', 'pengurus']))
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'sent' => 'Terkirim',
                        'failed' => 'Gagal',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Actions\Action::make('retry')
                    ->label('Kirim Ulang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Ulang Notifikasi WhatsApp?')
                    ->modalDescription('Pesan akan langsung dicoba kirimkan kembali ke nomor tujuan.')
                    ->visible(fn (NotifikasiLog $record): bool => in_array($record->status, ['failed', 'pending'], true))
                    ->action(function (NotifikasiLog $record): void {
                        try {
                            app(\App\Services\WhatsAppNotificationService::class)->kirimNotifikasi($record);

                            Notification::make()
                                ->title('Notifikasi Berhasil Dikirim')
                                ->body("Pesan telah berhasil terkirim ke {$record->nama_penerima} ({$record->no_hp_penerima}).")
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            $pesanError = $e->getMessage();
                            if (str_contains(strtolower($pesanError), 'session not ready')) {
                                $pesanError = 'Sesi WhatsApp belum terhubung (belum scan QR / pairing). Silakan buka menu WhatsApp Gateway untuk menautkan perangkat.';
                            }

                            Notification::make()
                                ->title('Pengiriman Gagal')
                                ->body($pesanError)
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('retryBulk')
                        ->label('Kirim Ulang Terpilih')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records): void {
                            $service = app(\App\Services\WhatsAppNotificationService::class);
                            $berhasil = 0;
                            $gagal = 0;
                            $lastError = null;

                            foreach ($records as $record) {
                                if (in_array($record->status, ['failed', 'pending'], true)) {
                                    try {
                                        $service->kirimNotifikasi($record);
                                        $berhasil++;
                                    } catch (\Throwable $e) {
                                        $gagal++;
                                        $lastError = $e->getMessage();
                                    }
                                }
                            }

                            if ($berhasil > 0 && $gagal === 0) {
                                Notification::make()
                                    ->title("{$berhasil} notifikasi berhasil dikirim")
                                    ->success()
                                    ->send();
                            } elseif ($berhasil > 0 && $gagal > 0) {
                                Notification::make()
                                    ->title("{$berhasil} terkirim, {$gagal} gagal")
                                    ->body($lastError)
                                    ->warning()
                                    ->send();
                            } else {
                                if (str_contains(strtolower((string) $lastError), 'session not ready')) {
                                    $lastError = 'Sesi WhatsApp belum terhubung (belum scan QR). Buka menu WhatsApp Gateway untuk menautkan perangkat.';
                                }

                                Notification::make()
                                    ->title("Pengiriman gagal ({$gagal} pesan)")
                                    ->body($lastError)
                                    ->danger()
                                    ->persistent()
                                    ->send();
                            }
                        }),
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNotifikasiLogs::route('/'),
        ];
    }
}
