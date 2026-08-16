<!-- 7.12 Galeri                                                    -->
    <!-- ============================================================ -->
    <section id="galeri" class="reveal scroll-mt-24 bg-page py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-label text-xs uppercase tracking-[0.25em] text-selat">Galeri</p>
                <h2 class="mt-4 font-display text-3xl font-bold text-songkok sm:text-4xl">Momen Kehidupan Pesantren</h2>
                <p class="mt-5 text-base leading-relaxed text-pegon/75">
                    Foto-foto kegiatan akan diisi dari dokumentasi resmi pesantren.
                </p>
            </div>

            <div class="mt-14 grid grid-cols-2 gap-4 lg:grid-cols-3">
                @foreach ([
                    ['ikon' => 'i-home', 'nama' => 'Asrama', 'besar' => 'row-span-2'],
                    ['ikon' => 'i-board', 'nama' => 'Kelas', 'besar' => ''],
                    ['ikon' => 'i-users', 'nama' => 'Kegiatan Santri', 'besar' => ''],
                    ['ikon' => 'i-graduation', 'nama' => 'Haflah & Wisuda', 'besar' => ''],
                    ['ikon' => 'i-heart', 'nama' => 'Reuni Alumni', 'besar' => ''],
                ] as $g)
                <div class="group relative flex flex-col items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-br from-songkok to-songkok-dark p-8 {{ $g['besar'] }} min-h-[10rem]">
                    <svg class="h-10 w-10 text-sepuh opacity-80"><use href="#{{ $g['ikon'] }}"/></svg>
                    <p class="mt-3 font-display text-lg font-bold text-white">{{ $g['nama'] }}</p>
                    <p class="mt-1 font-label text-[10px] uppercase tracking-widest text-white/50">Foto menyusul</p>
                </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="https://www.youtube.com/@miftahulihsanofficial" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-songkok px-7 py-3 text-sm font-bold text-white transition hover:bg-songkok-dark">
                    <svg class="h-5 w-5"><use href="#i-yt"/></svg>
                    Tonton di YouTube — Miftahul Ihsan Official
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
