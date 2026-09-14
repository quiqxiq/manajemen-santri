<x-filament-panels::page>
    <div
        x-data="whatsappGateway(@js($this->sessionId))"
        x-init="init()"
        class="flex flex-col gap-6"
    >
        {{-- ============================ Status Sesi ============================ --}}
        <x-filament::section icon="heroicon-o-signal">
            <x-slot name="heading">Status Koneksi WhatsApp Gateway</x-slot>
            <x-slot name="description">
                Status sesi terhubung langsung secara realtime melalui Server-Sent Events (SSE).
            </x-slot>

            <div class="flex flex-col gap-5">
                {{-- Status Badge & Info --}}
                <div class="flex flex-wrap items-center gap-3">
                    <div
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold shadow-xs transition-all duration-300"
                        :class="badgeClass(state.status)"
                    >
                        <span class="relative flex h-2 w-2">
                            <span
                                x-show="['ready', 'qr', 'authenticated'].includes(state.status)"
                                class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                :class="state.status === 'ready' ? 'bg-emerald-400' : 'bg-amber-400'"
                            ></span>
                            <span
                                class="relative inline-flex rounded-full h-2 w-2"
                                :class="dotClass(state.status)"
                            ></span>
                        </span>
                        <span x-text="statusLabel(state.status)"></span>
                    </div>

                    <span x-show="state.status === 'ready'" class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                        <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-emerald-500" />
                        Sesi aktif &amp; siap mengirim notifikasi ke wali santri/petugas.
                    </span>
                </div>

                {{-- Alert Box jika Sidecar Down --}}
                <div
                    x-show="state.status === 'sidecar_down'"
                    x-cloak
                    class="rounded-xl border border-red-200 bg-red-50/80 p-4 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-300 flex items-start gap-3 shadow-xs"
                >
                    <div class="p-2 rounded-lg bg-red-100 dark:bg-red-900/60 text-red-600 dark:text-red-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-5 h-5" />
                    </div>
                    <div class="flex-1 space-y-1">
                        <div class="font-semibold text-red-900 dark:text-red-200">Sidecar WhatsApp (Node.js) Tidak Berjalan</div>
                        <p class="text-xs text-red-700 dark:text-red-300">
                            Layanan background sidecar belum aktif pada port konfigurasi. Jalankan perintah berikut di terminal server:
                        </p>
                        <div class="mt-2 inline-flex items-center gap-2 bg-white dark:bg-gray-900 px-3 py-1.5 rounded-lg border border-red-200 dark:border-red-900/60 font-mono text-xs text-gray-800 dark:text-gray-200">
                            <code>php artisan whatsapp:sidecar:start</code>
                        </div>
                    </div>
                </div>

                {{-- Alert Box jika Sesi Belum Dibuat --}}
                <div
                    x-show="state.status === 'session_not_found'"
                    x-cloak
                    class="rounded-xl border border-amber-200 bg-amber-50/80 p-4 text-sm text-amber-900 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-200 flex items-start gap-3 shadow-xs"
                >
                    <div class="p-2 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 shrink-0">
                        <x-filament::icon icon="heroicon-o-information-circle" class="w-5 h-5" />
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-amber-900 dark:text-amber-200">Sesi Belum Dimulai</div>
                        <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">
                            Sesi WhatsApp belum terdaftar di sidecar. Klik tombol <strong>Mulai Sesi</strong> di bawah untuk menginisialisasi browser dan menampilkan QR Code / Kode Pairing.
                        </p>
                    </div>
                </div>

                {{-- Alert Box Error Lainnya --}}
                <div
                    x-show="state.error && state.status !== 'sidecar_down' && state.status !== 'session_not_found'"
                    x-cloak
                    class="rounded-xl border border-red-200 bg-red-50/80 p-3.5 text-xs text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-300 flex items-center gap-2"
                >
                    <x-filament::icon icon="heroicon-o-x-circle" class="w-4 h-4 text-red-500 shrink-0" />
                    <span x-text="state.error"></span>
                </div>

                {{-- Controls: Session ID & Actions --}}
                <div class="pt-2 border-t border-gray-100 dark:border-gray-800 flex flex-wrap gap-4 items-end justify-between">
                    <div class="flex flex-wrap gap-4 items-end">
                        <div class="flex flex-col gap-1.5 w-full sm:w-64">
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                Nama / ID Sesi WhatsApp
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="sessionId"
                                    @change="$wire.saveSessionId(sessionId)"
                                    placeholder="main"
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                />
                            </div>
                            <span class="text-[11px] text-gray-400">Default: <code>main</code> (ubah jika menggunakan nomor lain).</span>
                        </div>

                        <div class="flex flex-col gap-1.5 w-full sm:w-80">
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                Kontak Hotline / Pos Keamanan
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    wire:model.defer="nomorWaKeamanan"
                                    wire:change="saveNomorWaKeamanan($event.target.value)"
                                    placeholder="081234567890, 081298765432"
                                    class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                />
                            </div>
                            <span class="text-[11px] text-gray-400">Nomor pos satpam/hotline (bisa lebih dari 1 dipisahkan koma).</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <x-filament::button
                            wire:click="startSession"
                            icon="heroicon-m-play"
                            color="success"
                            x-show="['session_not_found','disconnected','error'].includes(state.status)"
                        >
                            Mulai Sesi
                        </x-filament::button>

                        <x-filament::button
                            wire:click="stopSession"
                            icon="heroicon-m-pause"
                            color="warning"
                            x-show="['qr','authenticated','ready'].includes(state.status)"
                        >
                            Hentikan Sementara
                        </x-filament::button>

                        <x-filament::button
                            color="danger"
                            icon="heroicon-m-trash"
                            wire:click="destroySession"
                            wire:confirm="Hapus sesi ini? Seluruh autentikasi lokal akan dibersihkan dan Anda perlu memindai QR code baru."
                            x-show="['qr','authenticated','ready'].includes(state.status)"
                        >
                            Hapus Sesi
                        </x-filament::button>

                        <x-filament::button
                            color="gray"
                            icon="heroicon-m-arrow-path"
                            @click="refresh()"
                        >
                            Muat Ulang
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </x-filament::section>

        {{-- ========================== Pairing Area (QR / Kode) ========================== --}}
        <div x-show="state.status === 'qr'" x-cloak>
            <x-filament::section icon="heroicon-o-qr-code">
                <x-slot name="heading">Tautkan Perangkat WhatsApp</x-slot>
                <x-slot name="description">
                    Tautkan akun WhatsApp petugas/pesantren dengan memindai kode QR atau memasukkan kode pairing 8 digit.
                </x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start pt-2">
                    {{-- Opsi 1: Pindai QR Code --}}
                    <div class="p-6 rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/40 flex flex-col items-center text-center gap-4 shadow-xs">
                        <div class="space-y-1">
                            <h4 class="font-semibold text-sm text-gray-900 dark:text-white flex items-center justify-center gap-1.5">
                                <x-filament::icon icon="heroicon-o-camera" class="w-4 h-4 text-primary-500" />
                                Opsi A: Pindai Kode QR
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Buka WhatsApp di HP &rarr; <strong>Setelan</strong> &rarr; <strong>Perangkat Tertaut</strong> &rarr; <strong>Tautkan Perangkat</strong>.
                            </p>
                        </div>

                        <div class="p-3 bg-white rounded-xl shadow-xs border border-gray-200 dark:border-gray-700">
                            <img
                                x-show="state.qr"
                                :src="state.qr"
                                alt="QR Code WhatsApp"
                                class="w-56 h-56 object-contain rounded-lg transition-all"
                            />
                            <div
                                x-show="!state.qr"
                                class="w-56 h-56 flex flex-col items-center justify-center gap-2 text-gray-400 text-xs"
                            >
                                <x-filament::icon icon="heroicon-o-arrow-path" class="w-8 h-8 animate-spin text-primary-500" />
                                <span>Menyiapkan QR Code…</span>
                            </div>
                        </div>

                        <span class="text-[11px] text-gray-400">QR Code akan diperbarui otomatis jika kedaluwarsa.</span>
                    </div>

                    {{-- Opsi 2: Kode Pairing 8 Digit --}}
                    <div class="p-6 rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/40 flex flex-col gap-4 shadow-xs">
                        <div class="space-y-1">
                            <h4 class="font-semibold text-sm text-gray-900 dark:text-white flex items-center gap-1.5">
                                <x-filament::icon icon="heroicon-o-device-phone-mobile" class="w-4 h-4 text-primary-500" />
                                Opsi B: Tautkan dengan Nomor HP (Kode Pairing)
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Masukkan nomor WhatsApp pengirim (contoh: <code>6281234567890</code>) untuk memperoleh kode pairing 8 digit.
                            </p>
                        </div>

                        <div class="flex gap-2 items-center">
                            <input
                                type="tel"
                                x-model="phoneNumber"
                                placeholder="6281234567890"
                                class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            />
                            <x-filament::button
                                size="sm"
                                icon="heroicon-m-paper-airplane"
                                @click="$wire.requestPairingCode(phoneNumber)"
                            >
                                Minta Kode
                            </x-filament::button>
                        </div>

                        <div x-show="state.pairingCode" class="mt-2 space-y-3 p-4 rounded-xl bg-white dark:bg-gray-800 border border-primary-200 dark:border-primary-900/50">
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300 block">
                                Masukkan kode berikut di WhatsApp HP Anda:
                            </span>
                            <div class="flex items-center gap-3">
                                <code
                                    x-text="state.pairingCode"
                                    class="font-mono text-2xl font-bold tracking-widest px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-900 text-primary-600 dark:text-primary-400 border border-gray-200 dark:border-gray-800"
                                ></code>
                                <x-filament::button
                                    size="sm"
                                    color="gray"
                                    icon="heroicon-m-clipboard-document"
                                    @click="navigator.clipboard && navigator.clipboard.writeText(state.pairingCode)"
                                >
                                    Salin
                                </x-filament::button>
                            </div>
                            <p class="text-[11px] text-gray-400">
                                Kode berlaku selama 60 detik sebelum digenerate ulang secara live.
                            </p>
                        </div>
                    </div>
                </div>
            </x-filament::section>
        </div>

        {{-- ========================= Petunjuk Penggunaan ========================= --}}
        <x-filament::section icon="heroicon-o-book-open" collapsible collapsed>
            <x-slot name="heading">Panduan Alur Kerja Notifikasi</x-slot>
            <x-slot name="description">
                Informasi teknis konfigurasi template, antrean latar belakang, dan pengawasan log.
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-600 dark:text-gray-300">
                <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200/80 dark:border-gray-800 space-y-1.5">
                    <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-1.5">
                        <x-filament::icon icon="heroicon-m-envelope" class="w-4 h-4 text-primary-500" />
                        1. Templat Notifikasi
                    </span>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Atur pesan otomatis untuk pengajuan perizinan, persetujuan, penolakan, kepulangan, tagihan, dan pelanggaran santri pada menu <strong>Templat WhatsApp</strong>.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200/80 dark:border-gray-800 space-y-1.5">
                    <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-1.5">
                        <x-filament::icon icon="heroicon-m-clock" class="w-4 h-4 text-amber-500" />
                        2. Antrean Pengiriman (Queue)
                    </span>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Pastikan queue worker berjalan di server: <code>php artisan queue:work</code> agar pesan terkirim di latar belakang tanpa memblokir antarmuka pengguna.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200/80 dark:border-gray-800 space-y-1.5">
                    <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-1.5">
                        <x-filament::icon icon="heroicon-m-document-text" class="w-4 h-4 text-emerald-500" />
                        3. Log Notifikasi &amp; Retry
                    </span>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Periksa status terkirim/gagal di menu <strong>Log Notifikasi</strong>. Sistem menyediakan aksi kirim ulang jika nomor tujuan sempat tidak aktif.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200/80 dark:border-gray-800 space-y-1.5">
                    <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-1.5">
                        <x-filament::icon icon="heroicon-m-server" class="w-4 h-4 text-blue-500" />
                        4. Daemon Sidecar
                    </span>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Sidecar berjalan di <code>http://127.0.0.1:3001</code>. Gunakan Process Manager seperti PM2 atau Supervisor di server produksi untuk memastikan selalu online.
                    </p>
                </div>
            </div>
        </x-filament::section>
    </div>

    <script>
        function whatsappGateway(sessionId) {
            return {
                sessionId: sessionId,
                phoneNumber: '',
                state: { status: 'loading', qr: null, pairingCode: null, error: null },
                pollTimer: null,
                isDestroyed: false,

                init() {
                    this.fetchState();
                    window.addEventListener('wa-refresh', () => this.fetchState());
                    window.addEventListener('beforeunload', () => this.cleanup());
                },

                cleanup() {
                    this.isDestroyed = true;
                    if (this.pollTimer) {
                        clearTimeout(this.pollTimer);
                        this.pollTimer = null;
                    }
                },

                fetchState() {
                    if (this.isDestroyed) return;
                    fetch(`/whatsapp/state/${encodeURIComponent(this.sessionId)}`)
                        .then((r) => r.json())
                        .then((data) => {
                            if (this.isDestroyed) return;
                            this.state = data;
                            this.scheduleNextPoll();
                        })
                        .catch(() => {
                            if (this.isDestroyed) return;
                            this.state = { status: 'sidecar_down', qr: null, pairingCode: null, error: 'Tidak dapat menghubungi server sidecar.' };
                            this.scheduleNextPoll(5000);
                        });
                },

                scheduleNextPoll(overrideMs) {
                    if (this.pollTimer) {
                        clearTimeout(this.pollTimer);
                        this.pollTimer = null;
                    }
                    if (this.isDestroyed) return;

                    // Saat status qr/pairing: polling cepat tiap 2.5 detik agar respon scan instan
                    // Saat ready: polling santai tiap 10 detik
                    // Saat error/down: polling tiap 5 detik
                    let delay = overrideMs;
                    if (!delay) {
                        if (['qr', 'initializing', 'authenticated'].includes(this.state.status)) {
                            delay = 2500;
                        } else if (this.state.status === 'ready') {
                            delay = 10000;
                        } else {
                            delay = 5000;
                        }
                    }

                    this.pollTimer = setTimeout(() => this.fetchState(), delay);
                },

                refresh() {
                    this.fetchState();
                },

                statusLabel(status) {
                    const labels = {
                        loading: 'Memeriksa status…',
                        sidecar_down: 'Sidecar Offline',
                        session_not_found: 'Sesi Belum Dimulai',
                        initializing: 'Menyiapkan Browser…',
                        qr: 'Menunggu Pindai QR / Pairing',
                        authenticated: 'Terautentikasi (Memuat Chat…)',
                        ready: 'Online & Terhubung',
                        disconnected: 'Terputus (Offline)',
                        auth_failure: 'Autentikasi Gagal',
                        error: 'Error Terjadi',
                    };
                    return labels[status] || status;
                },

                badgeClass(status) {
                    switch (status) {
                        case 'ready':
                            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800';
                        case 'qr':
                        case 'authenticated':
                            return 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-800';
                        case 'disconnected':
                        case 'auth_failure':
                        case 'error':
                        case 'sidecar_down':
                            return 'bg-red-100 text-red-800 dark:bg-red-950/60 dark:text-red-300 border border-red-300 dark:border-red-800';
                        default:
                            return 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-700';
                    }
                },

                dotClass(status) {
                    switch (status) {
                        case 'ready':
                            return 'bg-emerald-500';
                        case 'qr':
                        case 'authenticated':
                            return 'bg-amber-500';
                        case 'disconnected':
                        case 'auth_failure':
                        case 'error':
                        case 'sidecar_down':
                            return 'bg-red-500';
                        default:
                            return 'bg-gray-400';
                    }
                },
            };
        }
    </script>
</x-filament-panels::page>
