<?php

namespace App\Filament\Wali\Resources\PerizinanResource\Pages;

use App\Filament\Wali\Resources\PerizinanResource;
use App\Models\Santri;
use App\Services\PerizinanService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManagePerizinans extends ManageRecords
{
    protected static string $resource = PerizinanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajukan Perizinan')
                ->icon('heroicon-o-paper-airplane')
                ->before(function (array $data, Actions\CreateAction $action) {
                    // Aturan R1: santri dengan tunggakan tidak dapat mengajukan izin.
                    if (isset($data['santri_id'])) {
                        $santri = Santri::find($data['santri_id']);
                        if ($santri) {
                            $reason = app(PerizinanService::class)->checkCanApply($santri);
                            if ($reason) {
                                Notification::make()
                                    ->title('Pengajuan Tidak Dapat Diproses')
                                    ->body($reason)
                                    ->danger()
                                    ->send();
                                $action->halt();
                            }
                        }
                    }
                })
                ->mutateDataUsing(function (array $data): array {
                    // Status selalu diajukan; persetujuan hanya lewat admin/pengurus.
                    $data['status'] = 'diajukan';
                    $data['disetujui_oleh'] = null;

                    return $data;
                })
                ->after(function (array $data) {
                    Notification::make()
                        ->title('Perizinan Diajukan')
                        ->body('Pengajuan izin telah terkirim dan menunggu persetujuan pengurus.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
