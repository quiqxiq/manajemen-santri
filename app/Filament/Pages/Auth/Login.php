<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
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
            ->label('Username')
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    protected function getCredentialsFromFormData(#[SensitiveParameter] array $data): array
    {
        $user = User::where('username', $data['username'])->first();

        if ($user && $user->locked_until && $user->locked_until->isFuture()) {
            throw ValidationException::withMessages([
                'data.username' => 'Akun Anda terkunci sementara karena 3x gagal login. Coba lagi dalam beberapa menit.',
            ]);
        }

        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        $data = $this->form->getState();
        if (isset($data['username'])) {
            $user = User::where('username', $data['username'])->first();
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
