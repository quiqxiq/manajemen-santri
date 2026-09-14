<div class="space-y-5 text-sm">
    {{-- Banner Status Posisi Keberadaan Santri (Merah = Sedang Pulang, Hijau = Sudah Kembali) --}}
    @if($perizinan->posisi_santri === 'sedang_pulang')
        <div class="p-4 rounded-xl border border-red-300 bg-red-50/90 dark:border-red-900/60 dark:bg-red-950/40 text-red-900 dark:text-red-200 flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                </span>
                <div>
                    <h4 class="font-bold text-sm text-red-950 dark:text-red-100 flex items-center gap-2">
                        🔴 SANTRI SEDANG PULANG (DI LUAR PONDOK)
                    </h4>
                    <p class="text-xs text-red-700 dark:text-red-300 mt-0.5">
                        Santri masih berada di luar pondok. Status: <span class="font-semibold">{{ $perizinan->status_kepulangan_label }}</span>
                    </p>
                </div>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-200 text-red-900 dark:bg-red-900/60 dark:text-red-200 shrink-0">
                Tenggat: {{ $perizinan->sisa_waktu_label }}
            </span>
        </div>
    @elseif($perizinan->posisi_santri === 'sudah_kembali')
        <div class="p-4 rounded-xl border border-emerald-300 bg-emerald-50/90 dark:border-emerald-900/60 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-200 flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <div>
                    <h4 class="font-bold text-sm text-emerald-950 dark:text-emerald-100 flex items-center gap-2">
                        🟢 SANTRI SUDAH KEMBALI (DI PONDOK)
                    </h4>
                    <p class="text-xs text-emerald-700 dark:text-emerald-300 mt-0.5">
                        Kedatangan tiba: <span class="font-semibold">{{ $perizinan->tanggal_kembali?->translatedFormat('d F Y - H:i WIB') ?? '-' }}</span>
                        ({{ $perizinan->sisa_waktu_label }})
                    </p>
                </div>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-200 text-emerald-900 dark:bg-emerald-900/60 dark:text-emerald-200 shrink-0">
                Selesai
            </span>
        </div>
    @elseif($perizinan->posisi_santri === 'diajukan')
        <div class="p-3.5 rounded-xl border border-amber-300 bg-amber-50/90 dark:border-amber-900/60 dark:bg-amber-950/40 text-amber-900 dark:text-amber-200 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <span class="text-base">⏳</span>
                <div>
                    <h4 class="font-bold text-xs text-amber-950 dark:text-amber-100">
                        MENUNGGU PERSETUJUAN (SANTRI DI PONDOK)
                    </h4>
                    <p class="text-[11px] text-amber-700 dark:text-amber-300">
                        Pengajuan belum disetujui keamanan. Santri belum diperkenankan keluar pondok.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="p-3.5 rounded-xl border border-gray-300 bg-gray-50/90 dark:border-gray-800 dark:bg-gray-800/40 text-gray-700 dark:text-gray-300 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <span class="text-base">⚪</span>
                <div>
                    <h4 class="font-bold text-xs">
                        IZIN DITOLAK (SANTRI TETAP DI PONDOK)
                    </h4>
                    <p class="text-[11px] text-gray-500 mt-0.5">
                        {{ $perizinan->catatan_penolakan ?? 'Pengajuan izin tidak disetujui petugas.' }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Ringkasan Rincian Perizinan --}}
    <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/50 space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white text-base">
                    {{ $perizinan->santri?->nama_lengkap ?? 'Santri' }}
                </h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Kamar: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $perizinan->santri?->kamar?->nama_kamar ?? 'Tanpa Kamar' }}</span> • Jenis Izin: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $perizinan->jenis_izin_label }}</span>
                </p>
            </div>
            <div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                    @if($perizinan->posisi_santri === 'sedang_pulang') bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 border border-red-200 dark:border-red-800
                    @elseif($perizinan->posisi_santri === 'sudah_kembali') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800
                    @elseif($perizinan->status === 'diajukan') bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-200 dark:border-amber-800
                    @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 @endif">
                    {{ $perizinan->posisi_santri_label }}
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
                        $isImage = str_starts_with($media->mime_type ?? '', 'image/');
                        $previewUrl = route('perizinan.media.preview', $media);
                        $downloadUrl = route('perizinan.media.download', $media);
                    @endphp
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-800 shadow-sm flex flex-col hover:border-emerald-400 transition">
                        @if($isImage)
                            <a href="{{ $previewUrl }}" target="_blank" title="Klik untuk memperbesar foto" class="block group relative aspect-video bg-black/5 overflow-hidden cursor-pointer">
                                <img src="{{ $previewUrl }}" alt="{{ $media->file_name }}" class="w-full h-full object-cover transition duration-200 group-hover:scale-105" loading="lazy" />
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-medium gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Buka Foto
                                </div>
                            </a>
                        @else
                            <a href="{{ $previewUrl }}" target="_blank" title="Klik untuk membuka dokumen PDF" class="aspect-video bg-emerald-50 dark:bg-emerald-950/30 flex flex-col items-center justify-center p-3 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <span class="text-[10px] font-semibold mt-1 uppercase">Buka PDF</span>
                            </a>
                        @endif
                        <div class="p-2 text-xs flex-1 flex flex-col justify-between">
                            <span class="font-medium text-gray-800 dark:text-gray-200 truncate block" title="{{ $media->file_name }}">
                                {{ $media->file_name }}
                            </span>
                            <div class="mt-1 flex items-center justify-between text-[10px] text-gray-500">
                                <span>{{ $media->human_readable_size }}</span>
                                <div class="flex items-center gap-2">
                                    <a href="{{ $previewUrl }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 hover:underline font-semibold">
                                        Lihat
                                    </a>
                                    <span>•</span>
                                    <a href="{{ $downloadUrl }}" class="text-gray-600 dark:text-gray-400 hover:underline">
                                        Unduh
                                    </a>
                                </div>
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
                        $isImage = str_starts_with($media->mime_type ?? '', 'image/');
                        $previewUrl = route('perizinan.media.preview', $media);
                        $downloadUrl = route('perizinan.media.download', $media);
                    @endphp
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-800 shadow-sm flex flex-col hover:border-blue-400 transition">
                        @if($isImage)
                            <a href="{{ $previewUrl }}" target="_blank" title="Klik untuk memperbesar foto bukti kedatangan" class="block group relative aspect-video bg-black/5 overflow-hidden cursor-pointer">
                                <img src="{{ $previewUrl }}" alt="{{ $media->file_name }}" class="w-full h-full object-cover transition duration-200 group-hover:scale-105" loading="lazy" />
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-medium gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Buka Foto
                                </div>
                            </a>
                        @else
                            <a href="{{ $previewUrl }}" target="_blank" title="Klik untuk membuka dokumen PDF" class="aspect-video bg-blue-50 dark:bg-blue-950/30 flex flex-col items-center justify-center p-3 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <span class="text-[10px] font-semibold mt-1 uppercase">Buka PDF</span>
                            </a>
                        @endif
                        <div class="p-2 text-xs flex-1 flex flex-col justify-between">
                            <span class="font-medium text-gray-800 dark:text-gray-200 truncate block" title="{{ $media->file_name }}">
                                {{ $media->file_name }}
                            </span>
                            <div class="mt-1 flex items-center justify-between text-[10px] text-gray-500">
                                <span>{{ $media->human_readable_size }}</span>
                                <div class="flex items-center gap-2">
                                    <a href="{{ $previewUrl }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                                        Lihat
                                    </a>
                                    <span>•</span>
                                    <a href="{{ $downloadUrl }}" class="text-gray-600 dark:text-gray-400 hover:underline">
                                        Unduh
                                    </a>
                                </div>
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
