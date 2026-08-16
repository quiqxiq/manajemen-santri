<!-- 7.6 Kenapa Miftahul Ihsan                                      -->
    <!-- ============================================================ -->
    <section id="keunggulan" class="reveal scroll-mt-24 bg-page py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-label text-xs uppercase tracking-[0.25em] text-selat">Kenapa Miftahul Ihsan</p>
                <h2 class="mt-4 font-display text-3xl font-bold text-songkok sm:text-4xl">Alasan Memilih Kami</h2>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['ikon' => 'i-map-pin', 'judul' => 'Lingkungan Tenang', 'isi' => 'Berada di Desa Errabu, jauh dari kebisingan kota — suasana yang mendukung konsentrasi belajar dan ibadah.'],
                    ['ikon' => 'i-layers', 'judul' => 'Sistem Terpadu', 'isi' => 'Kitab kuning dan kurikulum nasional berjalan berdampingan dalam satu atap, tanpa saling meninggalkan.'],
                    ['ikon' => 'i-home', 'judul' => 'Pengasuhan Penuh Waktu', 'isi' => 'Pengajar tinggal di lingkungan asrama dan mengawasi santri secara langsung, siang dan malam.'],
                    ['ikon' => 'i-school', 'judul' => 'Jenjang Lengkap', 'isi' => 'PAUD, RA, MI, MTs, MA, hingga Madrasah Diniyah — satu yayasan dari anak usia dini sampai remaja.'],
                    ['ikon' => 'i-globe', 'judul' => 'Terbuka & Moderat', 'isi' => 'Pendidikan Islam tanpa isolasi: santri dibekali wawasan global dengan akar nilai lokal yang kuat.'],
                    ['ikon' => 'i-clock', 'judul' => 'Rekam Jejak Seabad', 'isi' => 'Hampir satu abad mengabdi, diwariskan turun-temurun melalui tiga generasi kepengasuhan.'],
                ] as $k)
                <div class="card-hover group rounded-2xl border border-sepuh/30 bg-page p-7">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-songkok/10 text-songkok transition group-hover:bg-songkok group-hover:text-white">
                        <svg class="h-7 w-7"><use href="#{{ $k['ikon'] }}"/></svg>
                    </div>
                    <h3 class="mt-5 font-display text-xl font-bold text-songkok">{{ $k['judul'] }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-pegon/75">{{ $k['isi'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
