<?php

namespace App\Filament\Resources\PengurusResource\Pages;

use App\Filament\Resources\PengurusResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePengurus extends ManageRecords
{
    protected static string $resource = PengurusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
