<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('username')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Peran / Hak Akses')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('last_login_at')
                    ->label('Login Terakhir')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Belum pernah')
                    ->sortable(),
                TextColumn::make('failed_login_attempts')
                    ->label('Gagal Login')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('locked_until')
                    ->label('Terkunci Sampai')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Filter Peran')
                    ->relationship('roles', 'name'),
                TernaryFilter::make('is_active')
                    ->label('Filter Status Aktif')
                    ->trueLabel('Hanya User Aktif')
                    ->falseLabel('Hanya User Nonaktif'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make()->label('Ubah'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus yang dipilih'),
                ]),
            ]);
    }
}
