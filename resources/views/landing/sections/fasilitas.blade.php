<!-- 7.10 Fasilitas                                                 -->
    <!-- ============================================================ -->
    <section id="fasilitas" class="reveal scroll-mt-24 bg-page py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-label text-xs uppercase tracking-[0.25em] text-selat">Fasilitas</p>
                <h2 class="mt-4 font-display text-3xl font-bold text-songkok sm:text-4xl">Sarana Penunjang Belajar</h2>
            </div>

            <ul class="mt-14 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ([
                    ['ikon' => 'i-home', 'nama' => 'Asrama Santri'],
                    ['ikon' => 'i-mosque', 'nama' => 'Musholla'],
                    ['ikon' => 'i-book', 'nama' => 'Perpustakaan'],
                    ['ikon' => 'i-monitor', 'nama' => 'Lab Komputer'],
                    ['ikon' => 'i-board', 'nama' => 'Ruang Kelas'],
                    ['ikon' => 'i-dumbbell', 'nama' => 'Olahraga & Keterampilan'],
                ] as $f)
                <li class="card-hover rounded-2xl border border-sepuh/30 bg-kitab px-4 py-8 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-songkok text-white">
                        <svg class="h-6 w-6"><use href="#{{ $f['ikon'] }}"/></svg>
                    </div>
                    <p class="mt-4 text-sm font-semibold leading-snug text-songkok">{{ $f['nama'] }}</p>
                </li>
                @endforeach
            </ul>
        </div>
    </section>

    <!-- ============================================================ -->
