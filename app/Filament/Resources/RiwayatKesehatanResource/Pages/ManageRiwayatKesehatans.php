<?php

namespace App\Filament\Resources\RiwayatKesehatanResource\Pages;

use App\Filament\Resources\RiwayatKesehatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRiwayatKesehatans extends ManageRecords
{
    protected static string $resource = RiwayatKesehatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
