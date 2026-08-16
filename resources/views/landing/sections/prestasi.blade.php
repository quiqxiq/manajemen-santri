<!-- 7.11 Prestasi Santri                                           -->
    <!-- ============================================================ -->
    <section id="prestasi" class="reveal scroll-mt-24 bg-kitab py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-label text-xs uppercase tracking-[0.25em] text-selat">Prestasi Santri</p>
                <h2 class="mt-4 font-display text-3xl font-bold text-songkok sm:text-4xl">Mengharumkan Nama di Berbagai Ajang</h2>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['nama' => 'Juara 2 Da\'i Cilik', 'peserta' => 'Santri MI Amelatul Qur\'ani', 'tingkat' => 'Nasional', 'penyelenggara' => 'ITS Surabaya', 'tahun' => '2021'],
                    ['nama' => 'Juara 1 Lomba Fotografi', 'peserta' => 'Siswa MTs', 'tingkat' => 'Nasional', 'penyelenggara' => 'Universitas Andalas', 'tahun' => '—'],
                    ['nama' => 'Juara Favorit Videografi', 'peserta' => 'Siswa MTs', 'tingkat' => 'Nasional', 'penyelenggara' => 'Universitas Slamet Riyadi Surakarta', 'tahun' => '—'],
                    ['nama' => 'Juara Favorit MTQ', 'peserta' => 'Santri', 'tingkat' => 'Nasional', 'penyelenggara' => 'Universitas Hang Tuah Surabaya', 'tahun' => '—'],
                    ['nama' => 'Lolos OLIMPABA Mapel Fiqih', 'peserta' => 'MI Miftahul Ihsan', 'tingkat' => 'Provinsi', 'penyelenggara' => 'OLIMPABA', 'tahun' => '—'],
                    ['nama' => 'Juara 2 Nasyid Islami', 'peserta' => 'Santri', 'tingkat' => 'Kecamatan Bluto', 'penyelenggara' => '—', 'tahun' => '—'],
                ] as $p)
                <div class="card-hover flex flex-col rounded-2xl border border-sepuh/30 bg-page p-7">
                    <div class="flex items-center justify-between">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-sepuh/15 text-sepuh">
                            <svg class="h-6 w-6"><use href="#i-trophy"/></svg>
                        </span>
                        <span class="rounded-full bg-selat/10 px-3 py-1 font-label text-[10px] uppercase tracking-widest text-selat">{{ $p['tingkat'] }}</span>
                    </div>
                    <h3 class="mt-5 font-display text-lg font-bold leading-snug text-songkok">{{ $p['nama'] }}</h3>
                    <p class="mt-2 text-sm text-pegon/70">{{ $p['peserta'] }}</p>
                    <div class="mt-4 flex-1 border-t border-sepuh/20 pt-4">
                        <p class="font-label text-[11px] uppercase tracking-widest text-pegon/50">Penyelenggara</p>
                        <p class="mt-0.5 text-sm font-medium text-pegon">{{ $p['penyelenggara'] }}</p>
                        <p class="mt-2 font-label text-xs text-sepuh">{{ $p['tahun'] !== '—' ? 'Tahun ' . $p['tahun'] : 'Tahun menyusul' }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="https://ponpes.miftahulihsan.sch.id/prestasi/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border-2 border-songkok px-7 py-3 text-sm font-bold text-songkok transition hover:bg-songkok hover:text-white">
                    Lihat Semua Prestasi
                    <svg class="h-4 w-4"><use href="#i-arrow-right"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
