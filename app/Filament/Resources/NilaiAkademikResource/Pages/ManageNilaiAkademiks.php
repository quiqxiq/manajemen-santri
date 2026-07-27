<?php

namespace App\Filament\Resources\NilaiAkademikResource\Pages;

use App\Filament\Resources\NilaiAkademikResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNilaiAkademiks extends ManageRecords
{
    protected static string $resource = NilaiAkademikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
