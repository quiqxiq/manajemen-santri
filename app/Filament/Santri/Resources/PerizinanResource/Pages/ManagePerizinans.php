<?php

namespace App\Filament\Santri\Resources\PerizinanResource\Pages;

use App\Filament\Santri\Resources\PerizinanResource;
use App\Services\PerizinanService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManagePerizinans extends ManageRecords
{
    protected static string $resource = PerizinanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $santri = auth()->user()?->santri;
                    if ($santri) {
                        $data['santri_id'] = $santri->id;
                    }
                    return $data;
                })
                ->before(function (Actions\CreateAction $action) {
                    $santri = auth()->user()?->santri;
                    if ($santri) {
                        $reason = app(PerizinanService::class)->checkCanApply($santri);
                        if ($reason) {
                            Notification::make()
                                ->title('Pengajuan Izin Ditolak Otomatis (R1)')
                                ->body($reason)
                                ->danger()
                                ->persistent()
                                ->send();
                            $action->halt();
                        }
                    }
                }),
        ];
    }
}
