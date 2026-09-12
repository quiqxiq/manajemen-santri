<div class="space-y-4 text-sm">
    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 bg-black/5 dark:bg-black/20 flex items-center justify-center p-2">
        <img
            src="{{ $perizinan->getFirstMediaUrl('bukti_kembali') }}"
            alt="Foto Bukti Kembali"
            class="max-h-96 w-auto rounded-lg object-contain shadow-sm"
        />
    </div>

    <div class="bg-gray-50 dark:bg-gray-800/60 p-4 rounded-xl space-y-2 border border-gray-100 dark:border-gray-800">
        <div class="flex justify-between items-center text-xs">
            <span class="text-gray-500 dark:text-gray-400">Waktu Kedatangan:</span>
            <span class="font-semibold text-gray-900 dark:text-white">
                {{ $perizinan->tanggal_kembali?->translatedFormat('d F Y - H:i WIB') ?? $perizinan->tanggal_kembali?->format('d/m/Y H:i') ?? '-' }}
            </span>
        </div>

        @if ($perizinan->catatan_kembali)
            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Catatan Kepulangan:</span>
                <p class="text-gray-700 dark:text-gray-300 italic text-xs">
                    "{{ $perizinan->catatan_kembali }}"
                </p>
            </div>
        @endif
    </div>
</div>
