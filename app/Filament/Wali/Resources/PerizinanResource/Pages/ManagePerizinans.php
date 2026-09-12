<?php

namespace App\Filament\Wali\Resources\PerizinanResource\Pages;

use App\Filament\Wali\Resources\PerizinanResource;
use App\Models\Perizinan;
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
                                $tagihanRecords = $santri->tagihan()
                                    ->with('pembayaran')
                                    ->whereIn('status', ['belum_lunas', 'sebagian'])
                                    ->orderBy('jatuh_tempo')
                                    ->get();

                                $tagihanList = $tagihanRecords->map(function ($t) {
                                    $periode = '-';
                                    if ($t->bulan) {
                                        try {
                                            $namaBulan = \Carbon\Carbon::create()->month((int) $t->bulan)->translatedFormat('F');
                                            $periode = "{$namaBulan} {$t->tahun}";
                                        } catch (\Throwable $e) {
                                            $periode = "Bulan {$t->bulan} / {$t->tahun}";
                                        }
                                    } elseif ($t->tahun) {
                                        $periode = "Tahun {$t->tahun}";
                                    }

                                    $sisa = $t->sisaTagihan();

                                    return [
                                        'jenis' => match ($t->jenis) {
                                            'spp' => 'SPP Bulanan',
                                            'daftar_ulang' => 'Daftar Ulang',
                                            'gedung' => 'Uang Gedung',
                                            'seragam' => 'Seragam & Kitab',
                                            'kegiatan' => 'Uang Kegiatan',
                                            default => ucfirst(str_replace('_', ' ', (string) $t->jenis)),
                                        },
                                        'periode' => $periode,
                                        'jatuh_tempo' => $t->jatuh_tempo ? $t->jatuh_tempo->format('d M Y') : null,
                                        'nominal' => 'Rp ' . number_format((float) $t->nominal, 0, ',', '.'),
                                        'sisa' => 'Rp ' . number_format($sisa, 0, ',', '.'),
                                        'sisa_numeric' => $sisa,
                                        'status' => $t->status,
                                    ];
                                })->toArray();

                                $totalTunggakan = (float) $tagihanRecords->sum(fn ($t) => $t->sisaTagihan());

                                $this->replaceMountedAction('peringatanTunggakan', [
                                    'nama_santri' => $santri->nama_lengkap,
                                    'tagihan_list' => $tagihanList,
                                    'total_tunggakan' => 'Rp ' . number_format($totalTunggakan, 0, ',', '.'),
                                ]);

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
                ->after(function (Perizinan $record) {
                    // Kirim notifikasi WhatsApp ke petugas keamanan / pengurus
                    app(PerizinanService::class)->kirimNotifikasiPengajuan($record);

                    Notification::make()
                        ->title('Perizinan Diajukan')
                        ->body('Pengajuan izin telah terkirim ke petugas keamanan dan menunggu persetujuan.')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function peringatanTunggakanAction(): Actions\Action
    {
        return Actions\Action::make('peringatanTunggakan')
            ->modalHeading('Pengajuan Tidak Dapat Diproses')
            ->modalIcon('heroicon-o-exclamation-triangle')
            ->modalIconColor('danger')
            ->modalWidth('lg')
            ->modalSubmitActionLabel('OK')
            ->modalCancelAction(false)
            ->modalContent(fn (array $arguments) => view('filament.wali.components.modal-tunggakan', [
                'namaSantri' => $arguments['nama_santri'] ?? 'Santri',
                'tagihanList' => $arguments['tagihan_list'] ?? [],
                'totalTunggakan' => $arguments['total_tunggakan'] ?? null,
            ]))
            ->action(fn () => null);
    }
}
