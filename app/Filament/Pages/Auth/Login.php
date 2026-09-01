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
        $isWaliPanel = filament()->getCurrentPanel()->getId() === 'wali';

        return TextInput::make('username')
            ->label('Username / No. HP')
            ->helperText($isWaliPanel
                ? 'Wali santri bisa masuk memakai nomor HP yang terdaftar.'
                : 'Gunakan username akun staf / pengurus.')
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

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata Sandi')
            ->password()
            ->revealable()
            ->required();
    }

    protected function getRememberFormComponent(): Component
    {
        return parent::getRememberFormComponent()
            ->label('Ingat saya di perangkat ini');
    }

    protected function getCredentialsFromFormData(#[SensitiveParameter] array $data): array
    {
        $user = $this->resolveUserByIdentifier($data['username']);

        if ($user) {
            if ($user->locked_until && $user->locked_until->isFuture()) {
                throw ValidationException::withMessages([
                    'data.username' => 'Akun Anda terkunci sementara karena 3x gagal login. Coba lagi dalam beberapa menit.',
                ]);
            }

            if (! $user->is_active) {
                throw ValidationException::withMessages([
                    'data.username' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi administrator.',
                ]);
            }
        }

        return [
            'username' => $user?->username ?? $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        $data = $this->form->getState();
        $panel = filament()->getCurrentPanel();

        if (isset($data['username'])) {
            $user = $this->resolveUserByIdentifier($data['username']);

            if ($user) {
                if (! $user->is_active) {
                    throw ValidationException::withMessages([
                        'data.username' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi administrator.',
                    ]);
                }

                if (! $user->canAccessPanel($panel)) {
                    $isWaliPanel = $panel->getId() === 'wali';
                    throw ValidationException::withMessages([
                        'data.username' => $isWaliPanel
                            ? 'Akun ini tidak memiliki hak akses sebagai wali santri.'
                            : 'Akun Anda tidak memiliki hak akses ke panel administrasi.',
                    ]);
                }

                $user->increment('failed_login_attempts');

                if ($user->failed_login_attempts >= 3) {
                    $user->update([
                        'locked_until' => now()->addMinutes(15),
                        'failed_login_attempts' => 0,
                    ]);

                    throw ValidationException::withMessages([
                        'data.username' => 'Akun Anda terkunci selama 15 menit karena 3x salah memasukkan kata sandi.',
                    ]);
                }
            }
        }

        throw ValidationException::withMessages([
            'data.username' => 'Username/No. HP atau kata sandi yang Anda masukkan salah.',
        ]);
    }
}
