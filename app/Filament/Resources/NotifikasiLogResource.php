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
                Tables\Columns\TextColumn::make('waliSantri.nama')
                    ->label('Wali Santri Penerima')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waliSantri.no_hp')
                    ->label('No. WA')
                    ->searchable(),
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
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'sent' => 'Terkirim',
                        'failed' => 'Gagal',
                    ]),
            ])
            ->recordActions([
                Actions\Action::make('retry')
                    ->label('Kirim Ulang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Ulang Notifikasi WhatsApp?')
                    ->modalDescription('Log akan dijadwalkan ulang ke antrean pengiriman WhatsApp.')
                    ->visible(fn (NotifikasiLog $record): bool => in_array($record->status, ['failed', 'pending'], true))
                    ->action(function (NotifikasiLog $record): void {
                        static::jadwalkanUlang($record);

                        Notification::make()
                            ->title('Notifikasi dijadwalkan ulang')
                            ->success()
                            ->send();
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
                            $count = 0;

                            foreach ($records as $record) {
                                if (in_array($record->status, ['failed', 'pending'], true)) {
                                    static::jadwalkanUlang($record);
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->title("{$count} notifikasi dijadwalkan ulang")
                                ->success()
                                ->send();
                        }),
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Setel ulang status menjadi pending lalu masukkan kembali ke antrean pengiriman.
     */
    private static function jadwalkanUlang(NotifikasiLog $record): void
    {
        $record->update([
            'status' => 'pending',
            'error_message' => null,
        ]);

        KirimNotifikasiWhatsApp::dispatch($record->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNotifikasiLogs::route('/'),
        ];
    }
}
