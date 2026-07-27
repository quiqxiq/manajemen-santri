<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotifikasiLogResource\Pages;
use App\Models\NotifikasiLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
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
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'sent' => 'Terkirim',
                        'failed' => 'Gagal',
                    ]),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNotifikasiLogs::route('/'),
        ];
    }
}
