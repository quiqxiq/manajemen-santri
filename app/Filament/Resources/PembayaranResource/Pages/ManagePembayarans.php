<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use App\Models\Pembayaran;
use App\Services\PembayaranService;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePembayarans extends ManageRecords
{
    protected static string $resource = PembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function (Pembayaran $record): void {
                    $service = app(PembayaranService::class);
                    $service->updateStatusTagihan($record->tagihan);
                    $service->kirimNotifikasiPembayaran($record);
                }),
        ];
    }
}
