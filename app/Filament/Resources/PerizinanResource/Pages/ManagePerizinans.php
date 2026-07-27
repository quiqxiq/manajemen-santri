<?php

namespace App\Filament\Resources\PerizinanResource\Pages;

use App\Filament\Resources\PerizinanResource;
use App\Models\Santri;
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
                ->before(function (array $data, Actions\CreateAction $action) {
                    if (isset($data['santri_id'])) {
                        $santri = Santri::find($data['santri_id']);
                        if ($santri) {
                            $reason = app(PerizinanService::class)->checkCanApply($santri);
                            if ($reason) {
                                Notification::make()
                                    ->title('Aturan Bisnis R1 Terlanggar')
                                    ->body($reason)
                                    ->danger()
                                    ->send();
                                $action->halt();
                            }
                        }
                    }
                }),
        ];
    }
}
