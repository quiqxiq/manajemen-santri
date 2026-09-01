<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('roles')
                            ->label('Peran / Hak Akses')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required(),
                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->helperText(fn (string $operation): string => $operation === 'edit'
                                ? 'Biarkan kosong jika tidak ingin mengubah kata sandi.'
                                : 'Minimal 6 karakter.')
                            ->minLength(6),
                        Toggle::make('is_active')
                            ->label('Status Akun Aktif')
                            ->helperText('Jika nonaktif, pengguna tidak dapat masuk ke aplikasi.')
                            ->default(true)
                            ->required(),
                    ])->columns(2),

                Section::make('Keamanan & Aktivitas (Opsional)')
                    ->schema([
                        TextInput::make('failed_login_attempts')
                            ->label('Jumlah Gagal Login')
                            ->numeric()
                            ->default(0),
                        DateTimePicker::make('locked_until')
                            ->label('Terkunci Sampai')
                            ->nullable(),
                        DateTimePicker::make('last_login_at')
                            ->label('Terakhir Login')
                            ->nullable()
                            ->readOnly(),
                    ])->columns(3)->collapsible()->collapsed(),
            ]);
    }
}
