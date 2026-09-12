<div class="space-y-4 text-sm">
    <div class="p-4 rounded-xl border border-red-200 bg-red-50/80 dark:bg-red-950/30 dark:border-red-900/50 flex items-start gap-3">
        <div class="p-2 rounded-lg bg-red-100 dark:bg-red-900/60 text-red-600 dark:text-red-400 shrink-0">
            <x-filament::icon
                icon="heroicon-o-exclamation-triangle"
                class="w-6 h-6"
            />
        </div>
        <div class="flex-1">
            <h4 class="font-semibold text-red-900 dark:text-red-200 text-base">
                Kewajiban Pembayaran Belum Selesai
            </h4>
            <p class="text-red-700 dark:text-red-300 mt-1 leading-relaxed">
                Pengajuan izin untuk santri <strong class="font-bold underline">{{ $namaSantri }}</strong> belum dapat diproses karena masih memiliki kewajiban administrasi/tagihan yang belum dilunasi.
            </p>
        </div>
    </div>

    @if (! empty($tagihanList))
        <div class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden shadow-xs">
            <div class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800/60 border-b border-gray-200 dark:border-gray-800 font-medium text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wider flex justify-between items-center">
                <span>Rincian Tagihan Tertunggak</span>
                <span class="text-[11px] font-normal normal-case text-gray-500 dark:text-gray-400">
                    {{ count($tagihanList) }} tagihan belum selesai
                </span>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($tagihanList as $tagihan)
                    <div class="px-4 py-3 flex items-center justify-between gap-4">
                        <div class="space-y-0.5">
                            <div class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <span>{{ $tagihan['jenis'] ?? 'Tagihan' }}</span>
                                @if (($tagihan['status'] ?? '') === 'sebagian')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                        Sebagian
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300">
                                        Belum Lunas
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <span>Periode: {{ $tagihan['periode'] ?? '-' }}</span>
                                @if (! empty($tagihan['jatuh_tempo']))
                                    <span>&bull;</span>
                                    <span>Jatuh Tempo: {{ $tagihan['jatuh_tempo'] }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-red-600 dark:text-red-400 text-sm">
                                {{ $tagihan['sisa'] ?? $tagihan['nominal'] ?? '-' }}
                            </div>
                            @if (($tagihan['status'] ?? '') === 'sebagian' && ! empty($tagihan['nominal']))
                                <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                    dari total {{ $tagihan['nominal'] }}
                                </div>
                            @else
                                <div class="text-[11px] text-gray-400 dark:text-gray-500">
                                    Nominal tagihan
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if (! empty($totalTunggakan))
                <div class="px-4 py-3 bg-red-50/50 dark:bg-red-950/20 border-t border-red-100 dark:border-red-900/40 flex items-center justify-between">
                    <span class="font-semibold text-gray-700 dark:text-gray-300 text-xs uppercase tracking-wider">
                        Total Tanggungan Belum Lunas
                    </span>
                    <span class="font-black text-red-600 dark:text-red-400 text-base">
                        {{ $totalTunggakan }}
                    </span>
                </div>
            @endif
        </div>
    @endif

    <div class="p-3.5 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 text-xs text-gray-600 dark:text-gray-400 flex items-start gap-2.5">
        <x-filament::icon
            icon="heroicon-o-information-circle"
            class="w-4 h-4 text-blue-500 shrink-0 mt-0.5"
        />
        <p>
            Sesuai kebijakan pondok pesantren, pengajuan izin santri mensyaratkan pelunasan administrasi keuangan. Silakan lakukan pembayaran melalui menu <strong>Tagihan & Pembayaran</strong> atau hubungi bagian administrasi keuangan pondok.
        </p>
    </div>
</div>
