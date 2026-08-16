<!-- 7.18 FAQ (opsional)                                            -->
    <!-- ============================================================ -->
    <section id="faq" class="reveal scroll-mt-24 bg-kitab py-20 lg:py-28">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="font-label text-xs uppercase tracking-[0.25em] text-selat">FAQ</p>
                <h2 class="mt-4 font-display text-3xl font-bold text-songkok sm:text-4xl">Pertanyaan yang Sering Diajukan</h2>
                <p class="mt-5 text-base leading-relaxed text-pegon/75">
                    Jawaban resmi sedang dikonfirmasi ke pihak pesantren. Untuk informasi tercepat,
                    silakan hubungi kami.
                </p>
            </div>

            <div class="mt-12 space-y-4">
                @foreach ([
                    'Berapa biaya pendaftaran dan SPP per jenjang?',
                    'Apakah semua santri wajib mondok / berasrama?',
                    'Berapa usia minimal untuk masuk tiap jenjang?',
                    'Apakah ada program tahfidz khusus?',
                    'Bagaimana sistem perizinan pulang santri?',
                ] as $q)
                <details class="group rounded-2xl border border-sepuh/30 bg-page open:border-sepuh">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 font-display text-base font-bold text-songkok">
                        {{ $q }}
                        <svg class="h-5 w-5 shrink-0 text-sepuh transition group-open:rotate-45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                    </summary>
                    <p class="px-6 pb-6 text-sm leading-relaxed text-pegon/75">
                        Jawaban resmi menyusul dari pesantren — hubungi kami via WhatsApp
                        <a href="https://wa.me/6287738888832" target="_blank" rel="noopener" class="font-semibold text-selat hover:underline">0877-3888-8832</a>.
                    </p>
                </details>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
