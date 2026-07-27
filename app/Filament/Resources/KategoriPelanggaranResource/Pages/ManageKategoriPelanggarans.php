<?php

namespace App\Filament\Resources\KategoriPelanggaranResource\Pages;

use App\Filament\Resources\KategoriPelanggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKategoriPelanggarans extends ManageRecords
{
    protected static string $resource = KategoriPelanggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
