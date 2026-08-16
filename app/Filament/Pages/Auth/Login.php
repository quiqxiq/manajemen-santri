<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use App\Models\WaliSantri;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

class Login extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getUsernameFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Username / No. HP')
            ->helperText('Wali santri bisa masuk memakai nomor HP yang terdaftar.')
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    /**
     * Cari user berdasarkan username ATAU nomor HP wali (format bebas:
     * 08xx, 62xx, +62, dengan strip/spasi — dibandingkan dalam bentuk digit).
     */
    private function resolveUserByIdentifier(string $identifier): ?User
    {
        $user = User::where('username', $identifier)->first();

        if ($user) {
            return $user;
        }

        $digits = ltrim(preg_replace('/\D+/', '', $identifier) ?? '', '0');

        if ($digits === '') {
            return null;
        }

        $user = User::where('username', $digits)->first();

        if ($user) {
            return $user;
        }

        // Cocokkan dengan nomor HP wali (normalisasi tanpa '0' di depan).
        foreach (WaliSantri::with('user')->get() as $wali) {
            $noHpDigits = ltrim(preg_replace('/\D+/', '', $wali->no_hp ?? '') ?? '', '0');

            if ($noHpDigits === $digits) {
                return $wali->user;
            }
        }

        return null;
    }

    protected function getCredentialsFromFormData(#[SensitiveParameter] array $data): array
    {
        $user = $this->resolveUserByIdentifier($data['username']);

        if ($user && $user->locked_until && $user->locked_until->isFuture()) {
            throw ValidationException::withMessages([
                'data.username' => 'Akun Anda terkunci sementara karena 3x gagal login. Coba lagi dalam beberapa menit.',
            ]);
        }

        return [
            'username' => $user?->username ?? $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        $data = $this->form->getState();

        if (isset($data['username'])) {
            $user = $this->resolveUserByIdentifier($data['username']);

            if ($user) {
                $user->increment('failed_login_attempts');

                if ($user->failed_login_attempts >= 3) {
                    $user->update([
                        'locked_until' => now()->addMinutes(15),
                        'failed_login_attempts' => 0,
                    ]);
                }
            }
        }

        throw ValidationException::withMessages([
            'data.username' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}
