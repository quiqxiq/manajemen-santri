<!-- 7.5 Visi & Misi                                               -->
    <!-- ============================================================ -->
    <section id="visi-misi" class="reveal scroll-mt-24 bg-kitab py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-label text-xs uppercase tracking-[0.25em] text-selat">Visi &amp; Misi</p>
                <h2 class="mt-4 font-display text-3xl font-bold text-songkok sm:text-4xl">Arah yang Kami Pegang</h2>
            </div>

            <div class="mt-12 grid gap-8 lg:grid-cols-[1.1fr_1fr]">
                <!-- Visi -->
                <div class="reveal rounded-2xl border border-sepuh/30 bg-page p-8 sm:p-10">
                    <p class="font-label text-xs uppercase tracking-widest text-sepuh">Visi</p>
                    <p class="mt-4 font-display text-2xl font-bold leading-relaxed text-songkok sm:text-[1.7rem]">
                        “Menjadi lembaga pendidikan Islam yang melahirkan generasi berilmu, berakhlak
                        karimah, dan mandiri — berpikir global, bertindak lokal.”
                    </p>
                    <p class="mt-5 text-xs text-pegon/50">Redaksi visi-misi resmi menyusul dari pihak pesantren.</p>
                </div>

                <!-- Misi -->
                <div class="reveal space-y-4" style="transition-delay: .1s">
                    @foreach ([
                        'Menyelenggarakan pendidikan diniyah berbasis kitab kuning dengan metode sorogan dan bandongan.',
                        'Mengintegrasikan kurikulum nasional dari PAUD hingga MA dalam satu naungan yayasan.',
                        'Membentuk santri yang disiplin, sederhana, mandiri, dan berakhlakul karimah.',
                        'Menebar manfaat kepada masyarakat melalui dakwah, pendidikan, dan layanan sosial-keagamaan.',
                    ] as $i => $misi)
                    <div class="flex items-start gap-4 rounded-xl border border-sepuh/30 bg-page px-5 py-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-songkok font-display text-sm font-bold text-white">{{ $i + 1 }}</span>
                        <p class="pt-1.5 text-sm leading-relaxed text-pegon/85">{{ $misi }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Nilai-nilai -->
            <div class="reveal mt-12">
                <p class="text-center font-label text-xs uppercase tracking-[0.25em] text-selat">Nilai-nilai yang dijaga</p>
                <ul class="mt-6 flex flex-wrap justify-center gap-3">
                    @foreach (['Keikhlasan', 'Kesederhanaan', 'Kemandirian', 'Ukhuwah Islamiyah', 'Ukhuwah Wathoniyah', 'Kepedulian Lingkungan'] as $nilai)
                    <li class="flex items-center gap-2 rounded-full border border-sepuh/40 bg-page px-5 py-2 text-sm font-medium text-songkok">
                        <svg class="h-4 w-4 text-sepuh"><use href="#i-check"/></svg>
                        {{ $nilai }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
