<!-- 7.16 Lokasi & Kontak                                           -->
    <!-- ============================================================ -->
    <section id="kontak" class="reveal scroll-mt-24 bg-page py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-label text-xs uppercase tracking-[0.25em] text-selat">Lokasi &amp; Kontak</p>
                <h2 class="mt-4 font-display text-3xl font-bold text-songkok sm:text-4xl">Kunjungi Kami</h2>
            </div>

            <div class="mt-14 grid gap-8 lg:grid-cols-2">
                <!-- Info kontak -->
                <div class="space-y-4">
                    <div class="flex items-start gap-4 rounded-2xl border border-sepuh/30 bg-kitab p-6">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-songkok text-white">
                            <svg class="h-6 w-6"><use href="#i-map-pin"/></svg>
                        </span>
                        <div>
                            <h3 class="font-display text-lg font-bold text-songkok">Alamat</h3>
                            <p class="mt-1 text-sm leading-relaxed text-pegon/75">
                                Jl. KH. Fathullah No. 99, Desa Errabu,<br>
                                Kec. Bluto, Kab. Sumenep, Jawa Timur 69466
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 rounded-2xl border border-sepuh/30 bg-kitab p-6">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-songkok text-white">
                            <svg class="h-6 w-6"><use href="#i-phone"/></svg>
                        </span>
                        <div>
                            <h3 class="font-display text-lg font-bold text-songkok">Telepon / WhatsApp</h3>
                            <a href="https://wa.me/6287738888832" target="_blank" rel="noopener" class="mt-1 inline-block text-sm text-pegon/75 transition hover:text-selat hover:underline">0877-3888-8832</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 rounded-2xl border border-sepuh/30 bg-kitab p-6">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-songkok text-white">
                            <svg class="h-6 w-6"><use href="#i-mail"/></svg>
                        </span>
                        <div>
                            <h3 class="font-display text-lg font-bold text-songkok">Email</h3>
                            <a href="mailto:miftahulihsanofficial@gmail.com" class="mt-1 inline-block text-sm text-pegon/75 transition hover:text-selat hover:underline">miftahulihsanofficial@gmail.com</a>
                        </div>
                    </div>

                    <!-- Media sosial -->
                    <div class="rounded-2xl border border-sepuh/30 bg-kitab p-6">
                        <h3 class="font-display text-lg font-bold text-songkok">Media Sosial</h3>
                        <ul class="mt-4 flex flex-wrap gap-3">
                            @foreach ([
                                ['ikon' => 'i-fb', 'nama' => 'Facebook', 'link' => 'https://facebook.com'],
                                ['ikon' => 'i-ig', 'nama' => 'Instagram', 'link' => 'https://instagram.com/miftahulihsan.official'],
                                ['ikon' => 'i-x', 'nama' => 'X (Twitter)', 'link' => 'https://x.com/yasmisumenep'],
                                ['ikon' => 'i-yt', 'nama' => 'YouTube', 'link' => 'https://www.youtube.com/@miftahulihsanofficial'],
                                ['ikon' => 'i-tt', 'nama' => 'TikTok', 'link' => 'https://tiktok.com/@miftahulihsan.official'],
                            ] as $s)
                            <li>
                                <a href="{{ $s['link'] }}" target="_blank" rel="noopener" aria-label="{{ $s['nama'] }}"
                                   class="flex h-11 w-11 items-center justify-center rounded-full border border-sepuh/40 bg-page text-songkok transition hover:bg-songkok hover:text-white">
                                    <svg class="h-5 w-5"><use href="#{{ $s['ikon'] }}"/></svg>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Peta -->
                <div class="overflow-hidden rounded-2xl border border-sepuh/30">
                    <iframe
                        title="Peta lokasi Pondok Pesantren Miftahul Ihsan"
                        src="https://maps.google.com/maps?q=-7.081528,113.756833&z=15&output=embed"
                        class="h-full min-h-[26rem] w-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </section>
