<div class="space-y-5 text-sm">
    {{-- Header Ringkasan Izin --}}
    <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/50 space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white text-base">
                    {{ $perizinan->santri?->nama_lengkap ?? 'Santri' }}
                </h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Kamar: {{ $perizinan->santri?->kamar?->nama_kamar ?? 'Tanpa Kamar' }} • Jenis Izin: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $perizinan->jenis_izin_label }}</span>
                </p>
            </div>
            <div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                    @if($perizinan->status === 'disetujui' && $perizinan->status_kepulangan === 'terlambat') bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300
                    @elseif($perizinan->status === 'disetujui' && $perizinan->status_kepulangan === 'hampir_habis') bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300
                    @elseif($perizinan->status === 'selesai' && $perizinan->status_kepulangan === 'selesai_terlambat') bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300
                    @elseif($perizinan->status === 'selesai') bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300
                    @elseif($perizinan->status === 'disetujui') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300
                    @elseif($perizinan->status === 'ditolak') bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300
                    @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 @endif">
                    {{ $perizinan->status_kepulangan_label }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 border-t border-gray-200/80 dark:border-gray-700/60 text-xs">
            <div>
                <span class="text-gray-500 dark:text-gray-400">Periode Izin:</span>
                <span class="font-medium text-gray-800 dark:text-gray-200 block">
                    {{ $perizinan->tanggal_mulai?->format('d/m/Y') }} s.d. {{ $perizinan->tanggal_selesai?->format('d/m/Y') }}
                </span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Batas Waktu Kepulangan:</span>
                <span class="font-medium text-gray-800 dark:text-gray-200 block">
                    {{ $perizinan->tanggal_selesai?->format('d/m/Y') }} pukul {{ $perizinan->jam_kembali_rencana ? substr($perizinan->jam_kembali_rencana, 0, 5) : '17:00' }} WIB
                    <span class="text-gray-500 dark:text-gray-400">({{ $perizinan->sisa_waktu_label }})</span>
                </span>
            </div>
            @if($perizinan->disetujuiOleh)
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Disetujui Oleh:</span>
                    <span class="font-medium text-gray-800 dark:text-gray-200 block">
                        {{ $perizinan->disetujuiOleh->name }}
                    </span>
                </div>
            @endif
            @if($perizinan->tanggal_kembali)
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Waktu Kedatangan Aktual:</span>
                    <span class="font-medium text-gray-800 dark:text-gray-200 block">
                        {{ $perizinan->tanggal_kembali->translatedFormat('d F Y - H:i WIB') }}
                    </span>
                </div>
            @endif
        </div>

        @if($perizinan->alasan)
            <div class="pt-2 border-t border-gray-200/80 dark:border-gray-700/60 text-xs">
                <span class="text-gray-500 dark:text-gray-400 font-medium block">Alasan Pengajuan Izin:</span>
                <p class="text-gray-700 dark:text-gray-300 mt-0.5">{{ $perizinan->alasan }}</p>
            </div>
        @endif
        @if($perizinan->catatan_penolakan)
            <div class="pt-2 border-t border-gray-200/80 dark:border-gray-700/60 text-xs text-rose-600 dark:text-rose-400">
                <span class="font-medium block">Alasan Penolakan:</span>
                <p class="mt-0.5">{{ $perizinan->catatan_penolakan }}</p>
            </div>
        @endif
    </div>

    {{-- Seksi 1: Dokumen Pendukung Awal Pengajuan --}}
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <h5 class="font-semibold text-gray-900 dark:text-white flex items-center gap-1.5 text-xs uppercase tracking-wider">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Dokumen Pendukung Pengajuan Izin
            </h5>
            <span class="text-xs text-gray-500 dark:text-gray-400">
                ({{ $perizinan->getMedia('dokumen_perizinan')->count() }} Berkas)
            </span>
        </div>

        @if($perizinan->getMedia('dokumen_perizinan')->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($perizinan->getMedia('dokumen_perizinan') as $media)
                    @php
                        $isImage = str_starts_with($media->mime_type, 'image/');
                    @endphp
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-800 shadow-sm flex flex-col">
                        @if($isImage)
                            <a href="{{ $media->getUrl() }}" target="_blank" class="block group relative aspect-video bg-black/5 overflow-hidden">
                                <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}" class="w-full h-full object-cover transition duration-200 group-hover:scale-105" />
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-medium">
                                    Buka Foto
                                </div>
                            </a>
                        @else
                            <div class="aspect-video bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center p-3 text-emerald-600 dark:text-emerald-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="p-2 text-xs flex-1 flex flex-col justify-between">
                            <span class="font-medium text-gray-800 dark:text-gray-200 truncate block" title="{{ $media->file_name }}">
                                {{ $media->file_name }}
                            </span>
                            <div class="mt-1 flex items-center justify-between text-[10px] text-gray-500">
                                <span>{{ $media->human_readable_size }}</span>
                                <a href="{{ $media->getUrl() }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 hover:underline font-semibold">
                                    Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-gray-500 dark:text-gray-400 italic bg-gray-50 dark:bg-gray-800/40 p-3 rounded-lg border border-dashed border-gray-200 dark:border-gray-700">
                Tidak ada dokumen pendukung (surat dokter/undangan) yang dilampirkan pada saat pengajuan.
            </p>
        @endif
    </div>

    {{-- Seksi 2: Foto Bukti & Dokumen Santri Kembali --}}
    <div class="space-y-2 pt-2 border-t border-gray-200 dark:border-gray-800">
        <div class="flex items-center justify-between">
            <h5 class="font-semibold text-gray-900 dark:text-white flex items-center gap-1.5 text-xs uppercase tracking-wider">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Foto & Dokumen Bukti Santri Kembali
            </h5>
            <span class="text-xs text-gray-500 dark:text-gray-400">
                ({{ $perizinan->getMedia('bukti_kembali')->count() }} Berkas)
            </span>
        </div>

        @if($perizinan->getMedia('bukti_kembali')->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($perizinan->getMedia('bukti_kembali') as $media)
                    @php
                        $isImage = str_starts_with($media->mime_type, 'image/');
                    @endphp
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-800 shadow-sm flex flex-col">
                        @if($isImage)
                            <a href="{{ $media->getUrl() }}" target="_blank" class="block group relative aspect-video bg-black/5 overflow-hidden">
                                <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}" class="w-full h-full object-cover transition duration-200 group-hover:scale-105" />
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-medium">
                                    Buka Foto
                                </div>
                            </a>
                        @else
                            <div class="aspect-video bg-blue-50 dark:bg-blue-950/30 flex items-center justify-center p-3 text-blue-600 dark:text-blue-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="p-2 text-xs flex-1 flex flex-col justify-between">
                            <span class="font-medium text-gray-800 dark:text-gray-200 truncate block" title="{{ $media->file_name }}">
                                {{ $media->file_name }}
                            </span>
                            <div class="mt-1 flex items-center justify-between text-[10px] text-gray-500">
                                <span>{{ $media->human_readable_size }}</span>
                                <a href="{{ $media->getUrl() }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                                    Lihat / Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-gray-500 dark:text-gray-400 italic bg-gray-50 dark:bg-gray-800/40 p-3 rounded-lg border border-dashed border-gray-200 dark:border-gray-700">
                Santri belum mengunggah foto bukti kedatangan kembali ke pondok.
            </p>
        @endif

        @if($perizinan->catatan_kembali)
            <div class="mt-3 bg-gray-50 dark:bg-gray-800/60 p-3 rounded-xl border border-gray-100 dark:border-gray-700/60">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block mb-0.5">Catatan Laporan Kedatangan:</span>
                <p class="text-gray-700 dark:text-gray-300 italic text-xs">
                    "{{ $perizinan->catatan_kembali }}"
                </p>
            </div>
        @endif
    </div>
</div>
