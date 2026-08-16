<!-- 7.4 Sejarah & Silsilah Kepengasuhan (rantai sanad)            -->
    <!-- ============================================================ -->
    <section id="sejarah" class="reveal scroll-mt-24 bg-page py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-label text-xs uppercase tracking-[0.25em] text-selat">Sejarah &amp; Silsilah</p>
                <h2 class="mt-4 font-display text-3xl font-bold text-songkok sm:text-4xl">Tiga Generasi, Satu Rantai Sanad</h2>
                <p class="mt-5 text-base leading-relaxed text-pegon/75">
                    Berdiri pada 1 April 1928, pesantren ini dirintis KH. Fathullah bin KH. Hasbullah — alumnus
                    Pondok Pesantren Annuqayah — dengan metode sorogan dan bandongan bagi santri berasrama,
                    plus sistem <em>kalong</em> bagi santri yang mengaji tanpa mondok penuh. Estafet pengasuhan
                    berlanjut hingga kini dalam satu rantai sanad keilmuan yang terjaga.
                </p>
            </div>

            <!-- Rantai Sanad (PRD 8.4) -->
            <div class="relative mt-16">
                <!-- Garis penghubung (desktop, digambar saat terlihat) -->
                <svg class="absolute left-0 right-0 top-8 hidden w-full lg:block" viewBox="0 0 100 2" preserveAspectRatio="none" aria-hidden="true">
                    <line x1="6" y1="1" x2="94" y2="1" pathLength="1" class="sanad-path" stroke="var(--color-sepuh)" stroke-width="1.5"/>
                </svg>

                <!-- Versi mobile: rel kiri -->
                <ol class="relative ml-5 space-y-10 border-l-2 border-sepuh/40 lg:hidden">
                    @foreach ([
                        ['no' => '01', 'nama' => 'KH. Fathullah bin KH. Hasbullah', 'tahun' => '1928 – 1970', 'ket' => 'Pendiri & pengasuh pertama · alumnus Annuqayah'],
                        ['no' => '02', 'nama' => 'K. Tawali', 'tahun' => '1970 – 1999', 'ket' => 'Pengasuh kedua · alumnus Annuqayah'],
                        ['no' => '03', 'nama' => 'K. Abbasi Rahman', 'tahun' => '1999 – sekarang', 'ket' => 'Pengasuh ketiga · alumnus Annuqayah & Sidogiri'],
                    ] as $s)
                    <li class="relative pl-12">
                        <span class="absolute -left-12 top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-sepuh ring-4 ring-page" aria-hidden="true"></span>
                        <div class="rounded-2xl border border-sepuh/30 bg-page p-6 shadow-sm transition hover:border-sepuh hover:shadow-lg">
                            <p class="font-label text-xs uppercase tracking-widest text-sepuh">Generasi {{ $s['no'] }}</p>
                            <h3 class="mt-2 font-display text-xl font-bold text-songkok">{{ $s['nama'] }}</h3>
                            <p class="mt-1 font-label text-sm text-selat">{{ $s['tahun'] }}</p>
                            <p class="mt-3 text-sm leading-relaxed text-pegon/75">{{ $s['ket'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ol>

                <!-- Versi desktop: 3 simpul -->
                <div class="hidden gap-8 lg:grid lg:grid-cols-3">
                    @foreach ([
                        ['no' => '01', 'nama' => 'KH. Fathullah bin KH. Hasbullah', 'tahun' => '1928 – 1970', 'ket' => 'Pendiri & pengasuh pertama · alumnus Annuqayah'],
                        ['no' => '02', 'nama' => 'K. Tawali', 'tahun' => '1970 – 1999', 'ket' => 'Pengasuh kedua · alumnus Annuqayah'],
                        ['no' => '03', 'nama' => 'K. Abbasi Rahman', 'tahun' => '1999 – sekarang', 'ket' => 'Pengasuh ketiga · alumnus Annuqayah & Sidogiri'],
                    ] as $s)
                    <div class="relative">
                        <div class="sanad-node absolute -top-8 left-1/2 flex h-16 w-16 -translate-x-1/2 items-center justify-center rounded-full bg-sepuh font-display text-lg font-bold text-pegon shadow-lg">
                            {{ $s['no'] }}
                        </div>
                        <div class="rounded-2xl border border-sepuh/30 bg-page p-6 pt-12 text-center shadow-sm transition hover:border-sepuh hover:shadow-xl">
                            <h3 class="font-display text-xl font-bold leading-snug text-songkok">{{ $s['nama'] }}</h3>
                            <p class="mt-2 font-label text-sm text-selat">{{ $s['tahun'] }}</p>
                            <p class="mt-3 text-sm leading-relaxed text-pegon/75">{{ $s['ket'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-14 text-center">
                <a href="https://ponpes.miftahulihsan.sch.id/sejarah-pondok-pesantren-miftahul-ihsan/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border-2 border-songkok px-7 py-3 text-sm font-bold text-songkok transition hover:bg-songkok hover:text-white">
                    Baca Sejarah Lengkap
                    <svg class="h-4 w-4"><use href="#i-arrow-right"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
