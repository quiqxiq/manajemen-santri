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
                Pengajuan izin untuk santri <strong class="font-bold underline">{{ $namaSantri }}</strong> belum dapat diajukan karena masih memiliki tanggungan pembayaran yang belum dilunasi.
            </p>
        </div>
    </div>

    @if (! empty($tagihanList))
        <div class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden shadow-xs">
            <div class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800/60 border-b border-gray-200 dark:border-gray-800 font-medium text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                Rincian Tagihan Belum Lunas
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($tagihanList as $tagihan)
                    <div class="px-4 py-3 flex items-center justify-between gap-4">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $tagihan['jenis'] ?? 'Tagihan' }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $tagihan['periode'] ?? '-' }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-red-600 dark:text-red-400">
                                {{ $tagihan['sisa'] ?? '-' }}
                            </div>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[10px] font-medium bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300">
                                Belum Lunas
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="p-3.5 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 text-xs text-gray-600 dark:text-gray-400 flex items-start gap-2.5">
        <x-filament::icon
            icon="heroicon-o-information-circle"
            class="w-4 h-4 text-blue-500 shrink-0 mt-0.5"
        />
        <p>
            Sesuai aturan pondok pesantren, pengajuan izin santri mensyaratkan tidak ada tunggakan administrasi. Silakan selesaikan pembayaran terlebih dahulu melalui menu <strong>Tagihan & Pembayaran</strong> atau menghubungi pihak tata usaha/keuangan pondok.
        </p>
    </div>
</div>
