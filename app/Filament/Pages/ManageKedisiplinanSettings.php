<?php

namespace App\Filament\Pages;

use App\Settings\KedisiplinanSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class ManageKedisiplinanSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Sistem & Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Rule Poin';

    protected static ?string $title = 'Pengaturan Rule Poin Kedisiplinan';

    protected string $view = 'filament.pages.manage-kedisiplinan-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(KedisiplinanSettings::class);
        $this->form->fill([
            'poin_peringatan' => $settings->poin_peringatan,
            'poin_kritis' => $settings->poin_kritis,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([
                Section::make('Ambang Batas Poin Kedisiplinan (Rule Engine R2 & R3)')
                    ->description('Nilai threshold poin pelanggaran yang memicu notifikasi otomatis ke Wali (R2) dan Pengasuh (R3).')
                    ->schema([
                        TextInput::make('poin_peringatan')
                            ->label('Threshold Poin Peringatan Wali (R2)')
                            ->numeric()
                            ->required(),
                        TextInput::make('poin_kritis')
                            ->label('Threshold Poin Kritis / Eskalasi Pengasuh (R3)')
                            ->numeric()
                            ->required(),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $settings = app(KedisiplinanSettings::class);
        $settings->poin_peringatan = (int) $data['poin_peringatan'];
        $settings->poin_kritis = (int) $data['poin_kritis'];
        $settings->save();

        Notification::make()
            ->title('Pengaturan Berhasil Disimpan')
            ->success()
            ->send();
    }
}
