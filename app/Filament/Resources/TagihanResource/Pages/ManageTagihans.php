<?php

namespace App\Filament\Resources\TagihanResource\Pages;

use App\Filament\Resources\TagihanResource;
use App\Models\Tagihan;
use App\Services\TagihanService;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Log;

class ManageTagihans extends ManageRecords
{
    protected static string $resource = TagihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function (Tagihan $record): void {
                    try {
                        app(TagihanService::class)->kirimNotifikasiWali($record);
                    } catch (\Throwable $e) {
                        Log::warning('Gagal membuat notifikasi tagihan #' . $record->id . ': ' . $e->getMessage());
                    }
                }),
        ];
    }
}
