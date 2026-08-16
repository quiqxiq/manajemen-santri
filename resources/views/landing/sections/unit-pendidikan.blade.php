<!-- 7.7 Unit Pendidikan                                            -->
    <!-- ============================================================ -->
    <section id="unit-pendidikan" class="reveal scroll-mt-24 bg-kitab py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-label text-xs uppercase tracking-[0.25em] text-selat">Unit Pendidikan</p>
                <h2 class="mt-4 font-display text-3xl font-bold text-songkok sm:text-4xl">Enam Jenjang dalam Satu Naungan</h2>
                <p class="mt-5 text-base leading-relaxed text-pegon/75">
                    Seluruh unit berada di bawah Yayasan Miftahul Ihsan (YASMI) — dari pendidikan anak usia dini
                    hingga pendidikan menengah dan diniyah.
                </p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['ikon' => 'i-heart', 'nama' => 'PAUD Miftahul Ihsan', 'jenjang' => 'Anak usia dini', 'isi' => 'Pengasuhan dan pembelajaran awal bagi anak usia dini.'],
                    ['ikon' => 'i-star', 'nama' => 'RA Miftahul Ihsan', 'jenjang' => 'Setara TK', 'isi' => 'Raudhatul Athfal — pendidikan anak usia dini bernuansa Islami.'],
                    ['ikon' => 'i-book', 'nama' => 'MI Miftahul Ihsan', 'jenjang' => 'Setara SD', 'isi' => 'Madrasah Ibtidaiyah dengan kurikulum nasional dan diniyah.', 'link' => 'https://mi.miftahulihsan.sch.id'],
                    ['ikon' => 'i-school', 'nama' => 'MTs Miftahul Ihsan', 'jenjang' => 'Setara SMP', 'isi' => 'Madrasah Tsanawiyah — lanjutan jenjang menengah pertama.', 'link' => 'https://mts.miftahulihsan.sch.id'],
                    ['ikon' => 'i-graduation', 'nama' => 'MA Al Ma\'arif Plus', 'jenjang' => 'Setara SMA · Swasta', 'isi' => 'Madrasah Aliyah dengan program plus dan penguatan diniyah.', 'link' => 'https://masalmaarifplus.sch.id'],
                    ['ikon' => 'i-mosque', 'nama' => 'Madrasah Diniyah', 'jenjang' => 'Kitab kuning', 'isi' => 'Pendidikan diniyah berbasis kitab kuning bagi santri.', 'link' => 'https://ponpes.miftahulihsan.sch.id/lembaga/'],
                ] as $u)
                <a href="{{ $u['link'] ?? '#unit-pendidikan' }}" target="{{ isset($u['link']) ? '_blank' : '_self' }}" rel="noopener"
                   class="card-hover group flex flex-col rounded-2xl border border-sepuh/30 bg-page p-7 {{ isset($u['link']) ? '' : 'cursor-default' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-songkok/10 text-songkok transition group-hover:bg-songkok group-hover:text-white">
                            <svg class="h-7 w-7"><use href="#{{ $u['ikon'] }}"/></svg>
                        </div>
                        <span class="rounded-full bg-selat/10 px-3 py-1 font-label text-[10px] uppercase tracking-widest text-selat">{{ $u['jenjang'] }}</span>
                    </div>
                    <h3 class="mt-5 font-display text-xl font-bold leading-snug text-songkok">{{ $u['nama'] }}</h3>
                    <p class="mt-3 flex-1 text-sm leading-relaxed text-pegon/75">{{ $u['isi'] }}</p>
                    @if (isset($u['link']))
                    <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-selat transition group-hover:gap-3">
                        Selengkapnya
                        <svg class="h-4 w-4"><use href="#i-arrow-right"/></svg>
                    </span>
                    @else
                    <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-pegon/40">
                        Segera hadir
                    </span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
