<footer class="bg-songkok-dark text-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-[1.3fr_1fr_1fr_1.2fr]">
            <!-- Kolom 1: identitas -->
            <div>
                <a href="#beranda" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Yayasan Miftahul Ihsan" class="h-12 w-12 rounded-full object-cover ring-2 ring-sepuh/60">
                    <span class="leading-tight">
                        <span class="block font-display text-lg font-bold">PP. Miftahul Ihsan</span>
                        <span class="block font-label text-[10px] uppercase tracking-widest text-sepuh">Yayasan Miftahul Ihsan · YASMI</span>
                    </span>
                </a>
                <p class="mt-5 max-w-sm text-sm leading-relaxed text-white/65">
                    Pesantren semi-salaf modern sejak 1928 — memadukan tradisi kitab kuning dengan
                    pendidikan formal nasional, dari PAUD hingga MA.
                </p>
                <p class="mt-4 flex items-start gap-2 text-sm text-white/65">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-sepuh"><use href="#i-map-pin"/></svg>
                    Jl. KH. Fathullah No. 99, Errabu, Bluto, Sumenep, Jawa Timur 69466
                </p>
            </div>

            <!-- Kolom 2: tautan cepat -->
            <nav aria-label="Tautan cepat">
                <h3 class="font-label text-xs uppercase tracking-widest text-sepuh">Tautan Cepat</h3>
                <ul class="mt-5 space-y-3 text-sm text-white/70">
                    <li><a href="#beranda" class="transition hover:text-sepuh">Beranda</a></li>
                    <li><a href="#sejarah" class="transition hover:text-sepuh">Sejarah</a></li>
                    <li><a href="#visi-misi" class="transition hover:text-sepuh">Visi &amp; Misi</a></li>
                    <li><a href="#unit-pendidikan" class="transition hover:text-sepuh">Unit Pendidikan</a></li>
                    <li><a href="#prestasi" class="transition hover:text-sepuh">Prestasi</a></li>
                    <li><a href="{{ url('/wali/login') }}" class="transition hover:text-sepuh">Login Wali Santri</a></li>
                    <li><a href="#kontak" class="transition hover:text-sepuh">Kontak</a></li>
                </ul>
            </nav>

            <!-- Kolom 3: unit pendidikan -->
            <nav aria-label="Unit pendidikan">
                <h3 class="font-label text-xs uppercase tracking-widest text-sepuh">Unit Pendidikan</h3>
                <ul class="mt-5 space-y-3 text-sm text-white/70">
                    <li><a href="#unit-pendidikan" class="transition hover:text-sepuh">PAUD &amp; RA Miftahul Ihsan</a></li>
                    <li><a href="https://mi.miftahulihsan.sch.id" target="_blank" rel="noopener" class="transition hover:text-sepuh">MI Miftahul Ihsan</a></li>
                    <li><a href="https://mts.miftahulihsan.sch.id" target="_blank" rel="noopener" class="transition hover:text-sepuh">MTs Miftahul Ihsan</a></li>
                    <li><a href="https://masalmaarifplus.sch.id" target="_blank" rel="noopener" class="transition hover:text-sepuh">MA Al Ma'arif Plus</a></li>
                    <li><a href="https://ponpes.miftahulihsan.sch.id/lembaga/" target="_blank" rel="noopener" class="transition hover:text-sepuh">Madrasah Diniyah</a></li>
                </ul>
            </nav>

            <!-- Kolom 4: kontak & sosmed -->
            <div>
                <h3 class="font-label text-xs uppercase tracking-widest text-sepuh">Kontak &amp; Media Sosial</h3>
                <ul class="mt-5 space-y-3 text-sm text-white/70">
                    <li>
                        <a href="https://wa.me/6287738888832" target="_blank" rel="noopener" class="inline-flex items-center gap-2 transition hover:text-sepuh">
                            <svg class="h-4 w-4 text-sepuh"><use href="#i-phone"/></svg> 0877-3888-8832
                        </a>
                    </li>
                    <li>
                        <a href="mailto:miftahulihsanofficial@gmail.com" class="inline-flex items-center gap-2 transition hover:text-sepuh">
                            <svg class="h-4 w-4 text-sepuh"><use href="#i-mail"/></svg> miftahulihsanofficial@gmail.com
                        </a>
                    </li>
                </ul>
                <ul class="mt-5 flex flex-wrap gap-3">
                    @foreach ([
                        ['ikon' => 'i-fb', 'nama' => 'Facebook', 'link' => 'https://facebook.com'],
                        ['ikon' => 'i-ig', 'nama' => 'Instagram', 'link' => 'https://instagram.com/miftahulihsan.official'],
                        ['ikon' => 'i-x', 'nama' => 'X (Twitter)', 'link' => 'https://x.com/yasmisumenep'],
                        ['ikon' => 'i-yt', 'nama' => 'YouTube', 'link' => 'https://www.youtube.com/@miftahulihsanofficial'],
                        ['ikon' => 'i-tt', 'nama' => 'TikTok', 'link' => 'https://tiktok.com/@miftahulihsan.official'],
                    ] as $s)
                    <li>
                        <a href="{{ $s['link'] }}" target="_blank" rel="noopener" aria-label="{{ $s['nama'] }}"
                           class="flex h-10 w-10 items-center justify-center rounded-full border border-sepuh/30 text-white/70 transition hover:border-sepuh hover:text-sepuh">
                            <svg class="h-4.5 w-4.5"><use href="#{{ $s['ikon'] }}"/></svg>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Baris bawah -->
        <div class="mt-14 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 text-sm text-white/50 sm:flex-row">
            <p>© <span id="year">2026</span> Yayasan Miftahul Ihsan (YASMI). Seluruh hak cipta.</p>
            <p class="font-label text-[11px] uppercase tracking-widest">المعهد مفتاح الاحسان الإسلامي · Berpikir Global, Bertindak Lokal</p>
        </div>
    </div>
</footer>