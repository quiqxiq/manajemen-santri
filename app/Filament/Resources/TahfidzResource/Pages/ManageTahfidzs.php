<?php

namespace App\Filament\Resources\TahfidzResource\Pages;

use App\Filament\Resources\TahfidzResource;
use App\Models\Santri;
use App\Services\TahfidzService;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTahfidzs extends ManageRecords
{
    protected static string $resource = TahfidzResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function (array $data) {
                    if (isset($data['santri_id'])) {
                        $santri = Santri::find($data['santri_id']);
                        if ($santri) {
                            app(TahfidzService::class)->evaluasiMilestoneTahfidz($santri);
                        }
                    }
                }),
        ];
    }
}
