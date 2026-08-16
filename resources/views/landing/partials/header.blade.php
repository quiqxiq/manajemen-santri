<header id="site-nav" class="fixed inset-x-0 top-0 z-50 bg-transparent">
    <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8" aria-label="Navigasi utama">
        <!-- Brand -->
        <a href="#beranda" class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Yayasan Miftahul Ihsan" class="h-12 w-12 rounded-full object-cover ring-2 ring-sepuh/60">
            <span class="leading-tight">
                <span class="block font-display text-lg font-bold text-white">PP. Miftahul Ihsan</span>
                <span class="block font-label text-[10px] uppercase tracking-widest text-sepuh">Yayasan Miftahul Ihsan · YASMI</span>
            </span>
        </a>

        <!-- Menu desktop -->
        <ul class="hidden items-center gap-1 lg:flex">
            <li><a href="#beranda" class="rounded-md px-3 py-2 text-sm font-medium text-white/90 transition hover:text-sepuh">Beranda</a></li>

            <li class="nav-dropdown relative">
                <button type="button" class="flex items-center gap-1 rounded-md px-3 py-2 text-sm font-medium text-white/90 transition hover:text-sepuh">
                    Profil
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="nav-dropdown-panel absolute left-0 top-full hidden w-52 pt-2">
                    <div class="rounded-xl border border-sepuh/30 bg-page p-2 shadow-xl">
                        <a href="#sejarah" class="block rounded-lg px-3 py-2 text-sm text-pegon transition hover:bg-kitab hover:text-songkok">Sejarah</a>
                        <a href="#visi-misi" class="block rounded-lg px-3 py-2 text-sm text-pegon transition hover:bg-kitab hover:text-songkok">Visi &amp; Misi</a>
                        <a href="#pengasuh" class="block rounded-lg px-3 py-2 text-sm text-pegon transition hover:bg-kitab hover:text-songkok">Pengasuh</a>
                    </div>
                </div>
            </li>

            <li><a href="#unit-pendidikan" class="rounded-md px-3 py-2 text-sm font-medium text-white/90 transition hover:text-sepuh">Unit Pendidikan</a></li>
            <li><a href="#layanan" class="rounded-md px-3 py-2 text-sm font-medium text-white/90 transition hover:text-sepuh">Layanan</a></li>

            <li class="nav-dropdown relative">
                <button type="button" class="flex items-center gap-1 rounded-md px-3 py-2 text-sm font-medium text-white/90 transition hover:text-sepuh">
                    Kehidupan Santri
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="nav-dropdown-panel absolute left-0 top-full hidden w-52 pt-2">
                    <div class="rounded-xl border border-sepuh/30 bg-page p-2 shadow-xl">
                        <a href="#kurikulum" class="block rounded-lg px-3 py-2 text-sm text-pegon transition hover:bg-kitab hover:text-songkok">Kurikulum &amp; Program</a>
                        <a href="#fasilitas" class="block rounded-lg px-3 py-2 text-sm text-pegon transition hover:bg-kitab hover:text-songkok">Fasilitas</a>
                        <a href="#prestasi" class="block rounded-lg px-3 py-2 text-sm text-pegon transition hover:bg-kitab hover:text-songkok">Prestasi</a>
                    </div>
                </div>
            </li>

            <li><a href="#berita" class="rounded-md px-3 py-2 text-sm font-medium text-white/90 transition hover:text-sepuh">Berita</a></li>
            <li><a href="#galeri" class="rounded-md px-3 py-2 text-sm font-medium text-white/90 transition hover:text-sepuh">Galeri</a></li>
            <li><a href="#kontak" class="rounded-md px-3 py-2 text-sm font-medium text-white/90 transition hover:text-sepuh">Kontak</a></li>

            <li class="ml-2">
                <a href="#ppdb" class="inline-flex items-center gap-2 rounded-full bg-sepuh px-5 py-2.5 text-sm font-bold text-pegon transition hover:bg-sepuh/90 hover:shadow-lg">
                    Daftar PPDB
                    <svg class="h-4 w-4"><use href="#i-arrow-right"/></svg>
                </a>
            </li>
        </ul>

        <!-- Tombol hamburger (mobile) -->
        <button id="nav-toggle" type="button" class="rounded-md p-2 text-white lg:hidden" aria-expanded="false" aria-label="Buka menu" aria-controls="mobile-menu">
            <svg class="h-7 w-7"><use href="#i-menu"/></svg>
        </button>
    </nav>

    <!-- Menu mobile -->
    <div id="mobile-menu" class="hidden border-t border-sepuh/20 bg-songkok-dark/95 backdrop-blur lg:hidden">
        <ul class="mx-auto max-w-7xl space-y-1 px-4 py-4">
            <li><a href="#beranda" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Beranda</a></li>
            <li><a href="#sejarah" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Sejarah</a></li>
            <li><a href="#visi-misi" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Visi &amp; Misi</a></li>
            <li><a href="#pengasuh" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Pengasuh</a></li>
            <li><a href="#unit-pendidikan" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Unit Pendidikan</a></li>
            <li><a href="#kurikulum" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Kurikulum &amp; Program</a></li>
            <li><a href="#layanan" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Layanan</a></li>
            <li><a href="#fasilitas" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Fasilitas</a></li>
            <li><a href="#prestasi" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Prestasi</a></li>
            <li><a href="#berita" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Berita</a></li>
            <li><a href="#galeri" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Galeri</a></li>
            <li><a href="#kontak" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/90 hover:bg-white/10">Kontak</a></li>
            <li class="pt-2">
                <a href="#ppdb" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-sepuh px-5 py-3 text-sm font-bold text-pegon">
                    Daftar PPDB Sekarang
                    <svg class="h-4 w-4"><use href="#i-arrow-right"/></svg>
                </a>
            </li>
        </ul>
    </div>
</header>