<!-- 7.2 Hero                                                      -->
    <!-- ============================================================ -->
    <section class="relative overflow-hidden bg-gradient-to-br from-songkok via-songkok to-songkok-dark text-white">
        <!-- Motif geometris Islami (pattern) -->
        <svg class="absolute inset-0 h-full w-full opacity-[0.08]" aria-hidden="true">
            <defs>
                <pattern id="star-pattern" width="64" height="64" patternUnits="userSpaceOnUse">
                    <path d="M32 4 L40 24 L60 32 L40 40 L32 60 L24 40 L4 32 L24 24 Z" fill="none" stroke="#fff" stroke-width="1"/>
                    <circle cx="32" cy="32" r="5" fill="none" stroke="#fff" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#star-pattern)"/>
        </svg>
        <!-- Cahaya radial lembut -->
        <div class="pointer-events-none absolute -top-40 right-0 h-[34rem] w-[34rem] rounded-full bg-sepuh/20 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-52 -left-32 h-[30rem] w-[30rem] rounded-full bg-selat/25 blur-3xl" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-7xl px-4 pb-20 pt-32 sm:px-6 lg:px-8 lg:pb-28 lg:pt-40">
            <div class="grid items-center gap-14 lg:grid-cols-2">
                <!-- Teks -->
                <div class="reveal">
                    <p class="flex items-center gap-2 font-label text-xs uppercase tracking-[0.25em] text-sepuh">
                        <span class="inline-block h-2 w-2 rounded-full bg-sepuh" aria-hidden="true"></span>
                        Sejak 1 April 1928 · Errabu, Bluto, Sumenep
                    </p>

                    <p class="arabic-accent mt-6 text-2xl text-white/80 sm:text-3xl" lang="ar" dir="rtl">المعهد مفتاح الاحسان الإسلامي</p>

                    <h1 class="mt-4 font-display text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                        Pondok Pesantren
                        <span class="block text-sepuh">Miftahul Ihsan</span>
                    </h1>

                    <p class="mt-4 font-display text-xl italic text-white/90 sm:text-2xl">“Berpikir Global, Bertindak Lokal”</p>

                    <p class="mt-6 max-w-xl text-base leading-relaxed text-white/75 sm:text-lg">
                        Pesantren semi-salaf modern yang memadukan tradisi kitab kuning dengan pendidikan
                        formal nasional — mendidik dari PAUD hingga MA di tengah suasana pedesaan yang
                        tenang, selama hampir satu abad.
                    </p>

                    <div class="mt-9 flex flex-col gap-4 sm:flex-row sm:items-center">
                        <a href="#ppdb" class="inline-flex items-center justify-center gap-2 rounded-full bg-sepuh px-7 py-3.5 text-base font-bold text-pegon transition hover:bg-sepuh/90 hover:shadow-xl">
                            Daftar PPDB Sekarang
                            <svg class="h-5 w-5"><use href="#i-arrow-right"/></svg>
                        </a>
                        <a href="https://wa.me/6287738888832" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-full border-2 border-white/30 px-7 py-3 text-base font-semibold text-white transition hover:border-sepuh hover:text-sepuh">
                            <svg class="h-5 w-5"><use href="#i-phone"/></svg>
                            Hubungi via WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Emblem logo -->
                <div class="reveal relative mx-auto hidden lg:block" style="transition-delay: .15s">
                    <div class="relative">
                        <div class="absolute inset-0 -m-10 rounded-full bg-sepuh/15 blur-2xl" aria-hidden="true"></div>
                        <img src="{{ asset('images/logo.png') }}" alt="Emblem Pondok Pesantren Miftahul Ihsan" class="relative mx-auto w-80 rounded-full ring-8 ring-sepuh/40" width="320" height="320">

                        <!-- Chip mengambang -->
                        <div class="absolute -left-16 top-8 rounded-2xl border border-sepuh/40 bg-songkok-dark/80 px-4 py-3 backdrop-blur">
                            <p class="font-label text-[10px] uppercase tracking-widest text-sepuh">Tradisi</p>
                            <p class="mt-0.5 text-sm font-semibold text-white">Kitab Kuning</p>
                        </div>
                        <div class="absolute -right-10 top-1/3 rounded-2xl border border-sepuh/40 bg-songkok-dark/80 px-4 py-3 backdrop-blur">
                            <p class="font-label text-[10px] uppercase tracking-widest text-sepuh">Kurikulum</p>
                            <p class="mt-0.5 text-sm font-semibold text-white">Nasional PAUD–MA</p>
                        </div>
                        <div class="absolute -bottom-4 left-1/2 flex -translate-x-1/2 items-center gap-2 rounded-full border border-sepuh/40 bg-songkok-dark/80 px-5 py-2.5 backdrop-blur">
                            <svg class="h-4 w-4 text-sepuh"><use href="#i-star"/></svg>
                            <span class="text-sm font-semibold text-white">Semi-Salaf Modern</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Strip statistik (angka terverifikasi PRD 7.2) -->
            <dl class="reveal mt-16 grid grid-cols-1 gap-8 border-t border-white/15 pt-10 sm:grid-cols-3 lg:mt-20">
                <div class="text-center sm:text-left">
                    <dd class="font-display text-4xl font-bold text-sepuh sm:text-5xl"><span data-count="98">0</span><span class="text-2xl"> thn</span></dd>
                    <dt class="mt-2 font-label text-xs uppercase tracking-widest text-white/70">Mengabdi sejak 1928</dt>
                </div>
                <div class="text-center sm:text-left">
                    <dd class="font-display text-4xl font-bold text-sepuh sm:text-5xl"><span data-count="3">0</span></dd>
                    <dt class="mt-2 font-label text-xs uppercase tracking-widest text-white/70">Generasi kepengasuhan</dt>
                </div>
                <div class="text-center sm:text-left">
                    <dd class="font-display text-4xl font-bold text-sepuh sm:text-5xl"><span data-count="6">0</span></dd>
                    <dt class="mt-2 font-label text-xs uppercase tracking-widest text-white/70">Unit pendidikan PAUD–MA</dt>
                </div>
            </dl>
        </div>
    </section>

    <!-- ============================================================ -->
