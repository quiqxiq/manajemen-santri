<!-- 7.15 PPDB                                                      -->
    <!-- ============================================================ -->
    <section id="ppdb" class="reveal scroll-mt-24 bg-gradient-to-br from-songkok to-songkok-dark py-20 text-white lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-label text-xs uppercase tracking-[0.25em] text-sepuh">Penerimaan Peserta Didik Baru</p>
                <h2 class="mt-4 font-display text-3xl font-bold sm:text-4xl">Daftar PPDB Sekarang</h2>
                <p class="mt-5 text-base leading-relaxed text-white/75">
                    Pilih jenjang tujuan, lalu daftar melalui portal pendaftaran masing-masing unit.
                </p>
            </div>

            <!-- Alur pendaftaran (motif rantai sanad: langkah) -->
            <ol class="mt-14 grid gap-6 sm:grid-cols-3 lg:grid-cols-5">
                @foreach (['Isi Formulir', 'Verifikasi Berkas', 'Tes / Wawancara', 'Pengumuman', 'Daftar Ulang'] as $i => $langkah)
                <li class="relative rounded-2xl border border-sepuh/30 bg-white/5 px-5 py-6 text-center backdrop-blur">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-sepuh font-display text-lg font-bold text-pegon">{{ $i + 1 }}</span>
                    <p class="mt-4 text-sm font-semibold text-white">{{ $langkah }}</p>
                </li>
                @endforeach
            </ol>

            <!-- CTA per jenjang -->
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ([
                    ['nama' => 'PAUD · RA · MTs', 'isi' => 'Pendaftaran melalui portal PPDB terpusat.', 'link' => 'https://ponpes.miftahulihsan.sch.id', 'cta' => 'Daftar Sekarang'],
                    ['nama' => 'MI Miftahul Ihsan', 'isi' => 'Formulir pendaftaran PPDB MI.', 'link' => 'https://mi.miftahulihsan.sch.id', 'cta' => 'Isi Formulir'],
                    ['nama' => 'MA Al Ma\'arif Plus', 'isi' => 'Pendaftaran melalui portal PPDB MA.', 'link' => 'https://masalmaarifplus.sch.id', 'cta' => 'Daftar Sekarang'],
                ] as $ppdb)
                <a href="{{ $ppdb['link'] }}" target="_blank" rel="noopener"
                   class="card-hover group rounded-2xl border border-sepuh/40 bg-white/5 p-7 backdrop-blur">
                    <h3 class="font-display text-xl font-bold text-white">{{ $ppdb['nama'] }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-white/70">{{ $ppdb['isi'] }}</p>
                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-sepuh transition group-hover:gap-3">
                        {{ $ppdb['cta'] }}
                        <svg class="h-4 w-4"><use href="#i-arrow-right"/></svg>
                    </span>
                </a>
                @endforeach
            </div>

            <p class="mt-10 text-center text-sm text-white/60">
                Butuh bantuan pendaftaran?
                <a href="https://wa.me/6287738888832" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 font-bold text-sepuh hover:underline">
                    <svg class="h-4 w-4"><use href="#i-phone"/></svg>
                    Chat WhatsApp 0877-3888-8832
                </a>
            </p>
        </div>
    </section>

    <!-- ============================================================ -->
