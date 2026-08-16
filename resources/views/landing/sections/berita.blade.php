<!-- 7.14 Berita & Kegiatan Terkini                                 -->
    <!-- ============================================================ -->
    <section id="berita" class="reveal scroll-mt-24 bg-page py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-end">
                <div>
                    <p class="font-label text-xs uppercase tracking-[0.25em] text-selat">Berita &amp; Kegiatan</p>
                    <h2 class="mt-4 font-display text-3xl font-bold text-songkok sm:text-4xl">Kabar Terkini dari Pesantren</h2>
                </div>
                <a href="https://miftahulihsan.sch.id" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border-2 border-songkok px-6 py-2.5 text-sm font-bold text-songkok transition hover:bg-songkok hover:text-white">
                    Lihat Semua Berita
                    <svg class="h-4 w-4"><use href="#i-arrow-right"/></svg>
                </a>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ([
                    ['kategori' => 'Pendidikan', 'ikon' => 'i-book'],
                    ['kategori' => 'Prestasi', 'ikon' => 'i-trophy'],
                    ['kategori' => 'Ubudiyah', 'ikon' => 'i-mosque'],
                ] as $b)
                <article class="card-hover overflow-hidden rounded-2xl border border-sepuh/30 bg-page">
                    <div class="flex aspect-video items-center justify-center bg-gradient-to-br from-songkok/90 to-songkok-dark">
                        <svg class="h-12 w-12 text-sepuh/80"><use href="#{{ $b['ikon'] }}"/></svg>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3">
                            <span class="rounded-full bg-sepuh/15 px-3 py-1 font-label text-[10px] uppercase tracking-widest text-sepuh">{{ $b['kategori'] }}</span>
                            <span class="font-label text-[11px] uppercase tracking-widest text-pegon/40">Tanggal menyusul</span>
                        </div>
                        <h3 class="mt-4 font-display text-lg font-bold leading-snug text-songkok">Judul berita menyusul</h3>
                        <p class="mt-2 text-sm leading-relaxed text-pegon/70">
                            Ringkasan singkat akan diisi dari feed situs resmi pesantren.
                        </p>
                    </div>
                </article>
                @endforeach
            </div>

            <p class="mt-6 text-center font-label text-[11px] uppercase tracking-widest text-pegon/40">
                Kartu contoh — menunggu sinkronisasi berita dari situs resmi
            </p>
        </div>
    </section>

    <!-- ============================================================ -->
