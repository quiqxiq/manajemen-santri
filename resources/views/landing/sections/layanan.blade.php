<!-- 7.9 Layanan Masyarakat                                         -->
    <!-- ============================================================ -->
    <section id="layanan" class="reveal scroll-mt-24 bg-kitab py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-label text-xs uppercase tracking-[0.25em] text-selat">Layanan Masyarakat</p>
                <h2 class="mt-4 font-display text-3xl font-bold text-songkok sm:text-4xl">Mengabdi untuk Umat</h2>
                <p class="mt-5 text-base leading-relaxed text-pegon/75">
                    Di samping pendidikan, pesantren aktif dalam dakwah, pemberdayaan ekonomi umat, dan
                    pelayanan keagamaan bagi masyarakat sekitar Errabu dan Bluto.
                </p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['ikon' => 'i-quran', 'judul' => 'TPQ', 'isi' => 'Taman Pendidikan Al-Qur\'an untuk pembelajaran baca Al-Qur\'an anak-anak.'],
                    ['ikon' => 'i-coins', 'judul' => 'Lembaga Amil Zakat', 'isi' => 'Penghimpunan dan penyaluran zakat, infak, dan sedekah.'],
                    ['ikon' => 'i-chat', 'judul' => 'Konseling', 'isi' => 'Layanan bimbingan konseling "Inspirasi Diri" bagi masyarakat.'],
                    ['ikon' => 'i-heart', 'judul' => 'Muallaf Center', 'isi' => 'Pembinaan dan pendampingan bagi saudara-saudara mualaf (YASMI Muallaf Center).'],
                ] as $l)
                <div class="card-hover rounded-2xl border border-sepuh/30 bg-page p-7 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-songkok/10 text-songkok">
                        <svg class="h-7 w-7"><use href="#{{ $l['ikon'] }}"/></svg>
                    </div>
                    <h3 class="mt-5 font-display text-lg font-bold text-songkok">{{ $l['judul'] }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-pegon/75">{{ $l['isi'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
