<?php

namespace App\Filament\Resources\PenyakitBawaanResource\Pages;

use App\Filament\Resources\PenyakitBawaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePenyakitBawaans extends ManageRecords
{
    protected static string $resource = PenyakitBawaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
