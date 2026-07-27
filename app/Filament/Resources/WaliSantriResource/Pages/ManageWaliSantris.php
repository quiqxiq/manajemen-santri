<?php

namespace App\Filament\Resources\WaliSantriResource\Pages;

use App\Filament\Resources\WaliSantriResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWaliSantris extends ManageRecords
{
    protected static string $resource = WaliSantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
