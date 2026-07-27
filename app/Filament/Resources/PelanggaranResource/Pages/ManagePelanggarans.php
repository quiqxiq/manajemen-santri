<?php

namespace App\Filament\Resources\PelanggaranResource\Pages;

use App\Filament\Resources\PelanggaranResource;
use App\Models\Pelanggaran;
use App\Services\KedisiplinanService;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePelanggarans extends ManageRecords
{
    protected static string $resource = PelanggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function (Pelanggaran $record) {
                    app(KedisiplinanService::class)->evaluasiPoinSantri($record->santri, $record);
                }),
        ];
    }
}
