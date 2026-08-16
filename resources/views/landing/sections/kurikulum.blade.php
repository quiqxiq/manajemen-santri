<!-- 7.8 Kurikulum & Program                                        -->
    <!-- ============================================================ -->
    <section id="kurikulum" class="reveal scroll-mt-24 bg-page py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <p class="flex items-center gap-2 font-label text-xs uppercase tracking-[0.25em] text-selat">
                        <span class="inline-block h-2 w-2 rounded-full bg-selat" aria-hidden="true"></span>
                        Kurikulum &amp; Program
                    </p>
                    <h2 class="mt-4 font-display text-3xl font-bold leading-tight text-songkok sm:text-4xl">Kitab Kuning dan<br>Kurikulum Nasional,<br>Bersama-sama</h2>
                    <p class="mt-6 text-base leading-relaxed text-pegon/75">
                        Sistem pendidikan terpadu: santri mengkaji kitab kuning dengan metode klasik
                        <em>sorogan</em> dan <em>bandongan</em>, sekaligus mengikuti pendidikan formal sesuai
                        standar nasional. Dua jalur ini tidak berjalan terpisah, melainkan saling menguatkan.
                    </p>

                    <div class="mt-8 rounded-2xl border border-sepuh/40 bg-kitab p-6">
                        <p class="flex items-start gap-3 text-sm leading-relaxed text-pegon/80">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-sepuh"><use href="#i-calendar"/></svg>
                            <span><strong class="text-songkok">Jadwal harian santri</strong> menunggu data resmi dari pesantren — contoh format akan ditambahkan setelah konfirmasi.</span>
                        </p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ([
                        ['ikon' => 'i-book', 'judul' => 'Pengajian Kitab', 'isi' => 'Kajian kitab kuning dengan metode sorogan dan bandongan.'],
                        ['ikon' => 'i-mic', 'judul' => 'Halaqah Ilmiah', 'isi' => 'Forum diskusi dan pengajian ilmiah di kalangan santri.'],
                        ['ikon' => 'i-quran', 'judul' => 'Tahfidzul Qur\'an', 'isi' => 'Program hafalan Al-Qur\'an dengan bimbingan intensif.'],
                        ['ikon' => 'i-briefcase', 'judul' => 'Kemandirian & Keterampilan', 'isi' => 'Pembinaan hidup mandiri dan keterampilan praktis santri.'],
                    ] as $prog)
                    <div class="card-hover rounded-2xl border border-sepuh/30 bg-kitab p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-songkok text-white">
                            <svg class="h-6 w-6"><use href="#{{ $prog['ikon'] }}"/></svg>
                        </div>
                        <h3 class="mt-4 font-display text-lg font-bold text-songkok">{{ $prog['judul'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-pegon/75">{{ $prog['isi'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
