<x-filament-panels::page>
    <div
        x-data="whatsappGateway(@js($this->sessionId))"
        x-init="init()"
        style="display:flex;flex-direction:column;gap:1.25rem;"
    >
        {{-- ============================ Status ============================ --}}
        <x-filament::section>
            <x-slot name="heading">Status Gateway WhatsApp</x-slot>
            <x-slot name="description">
                Status sesi diperbarui live lewat Server-Sent Events (SSE), tanpa polling.
            </x-slot>

            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div style="display:flex;flex-wrap:wrap;align-items:center;gap:1rem;">
                    <span
                        x-show="state.status"
                        :style="'display:inline-flex;align-items:center;gap:.4rem;padding:.3rem .75rem;border-radius:9999px;font-size:.78rem;font-weight:600;color:#fff;background:' + badgeColor(state.status)"
                        x-text="statusLabel(state.status)"
                    ></span>
                    <span x-show="state.status === 'ready'" style="font-size:.8rem;color:var(--fi-color-gray-500);">Notifikasi akan dikirim dari sesi ini.</span>
                </div>

                <div x-show="state.status === 'sidecar_down'" style="padding:.75rem 1rem;border-radius:.75rem;background:#fef2f2;color:#b91c1c;font-size:.85rem;">
                    Sidecar WhatsApp (Node) tidak berjalan. Jalankan dari terminal server:
                    <code style="display:block;margin-top:.35rem;font-family:monospace;">php artisan whatsapp:sidecar:start</code>
                </div>

                <div x-show="state.status === 'session_not_found'" style="padding:.75rem 1rem;border-radius:.75rem;background:#fffbeb;color:#b45309;font-size:.85rem;">
                    Sesi belum pernah dimulai. Klik <strong>Mulai Sesi</strong> untuk menampilkan QR / kode pairing.
                </div>

                <div x-show="state.error && state.status !== 'sidecar_down' && state.status !== 'session_not_found'" style="padding:.75rem 1rem;border-radius:.75rem;background:#fef2f2;color:#b91c1c;font-size:.85rem;" x-text="state.error"></div>

                <div style="display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-end;">
                    <div style="display:flex;flex-direction:column;gap:.3rem;">
                        <label style="font-size:.8rem;color:var(--fi-color-gray-500);">ID Sesi WhatsApp</label>
                        <input
                            type="text"
                            x-model="sessionId"
                            @change="$wire.saveSessionId(sessionId)"
                            style="padding:.5rem .75rem;border:1px solid var(--fi-color-gray-300);border-radius:.5rem;background:transparent;color:inherit;"
                        />
                    </div>

                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                        <x-filament::button
                            wire:click="startSession"
                            x-show="['session_not_found','disconnected','error'].includes(state.status)"
                        >
                            Mulai Sesi
                        </x-filament::button>

                        <x-filament::button
                            wire:click="stopSession"
                            x-show="['qr','authenticated','ready'].includes(state.status)"
                        >
                            Hentikan (Simpan Auth)
                        </x-filament::button>

                        <x-filament::button
                            color="danger"
                            wire:click="destroySession"
                            wire:confirm="Hapus sesi? Autentikasi akan dihapus dan perlu QR baru saat start berikutnya."
                            x-show="['qr','authenticated','ready'].includes(state.status)"
                        >
                            Hapus Sesi
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </x-filament::section>

        {{-- ========================== Pairing ========================== --}}
        <x-filament::section x-show="state.status === 'qr'">
            <x-slot name="heading">Tautkan Nomor WhatsApp</x-slot>
            <x-slot name="description">
                Dua cara: pindai QR di WhatsApp → Setelan → Perangkat Tertaut → Tautkan Perangkat,
                atau gunakan kode pairing (WhatsApp → Setelan → Perangkat Tertaut → Tautkan dengan nomor telepon).
            </x-slot>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.25rem;">
                <div style="display:flex;flex-direction:column;align-items:center;gap:.6rem;">
                    <div style="font-weight:600;font-size:.85rem;">Pindai QR</div>
                    <img
                        x-show="state.qr"
                        :src="state.qr"
                        alt="QR Code WhatsApp"
                        style="width:220px;height:220px;border:1px solid var(--fi-color-gray-200);border-radius:.75rem;padding:.5rem;background:#fff;"
                    />
                    <span x-show="!state.qr" style="font-size:.8rem;color:var(--fi-color-gray-500);">Menunggu QR dibuat…</span>
                </div>

                <div style="display:flex;flex-direction:column;gap:.75rem;">
                    <div style="font-weight:600;font-size:.85rem;">Kode Pairing</div>
                    <div style="display:flex;gap:.5rem;align-items:center;">
                        <input
                            type="tel"
                            x-model="phoneNumber"
                            placeholder="6281234567890"
                            style="flex:1;padding:.5rem .75rem;border:1px solid var(--fi-color-gray-300);border-radius:.5rem;background:transparent;color:inherit;"
                        />
                        <x-filament::button
                            size="sm"
                            @click="$wire.requestPairingCode(phoneNumber)"
                        >
                            Minta Kode
                        </x-filament::button>
                    </div>

                    <div x-show="state.pairingCode" style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <code
                            x-text="state.pairingCode"
                            style="font-family:monospace;font-size:1.4rem;font-weight:700;letter-spacing:.35rem;padding:.5rem .9rem;border-radius:.5rem;background:var(--fi-color-gray-100);"
                        ></code>
                        <x-filament::button
                            size="sm"
                            color="gray"
                            @click="navigator.clipboard && navigator.clipboard.writeText(state.pairingCode)"
                        >
                            Salin
                        </x-filament::button>
                    </div>

                    <span x-show="state.pairingCode" style="font-size:.78rem;color:var(--fi-color-gray-500);">
                        Kode diperbarui otomatis tiap 60 detik hingga perangkat tertaut (tampil live).
                    </span>
                </div>
            </div>
        </x-filament::section>

        {{-- ========================= Petunjuk ========================= --}}
        <x-filament::section>
            <x-slot name="heading">Alur Pengiriman Notifikasi</x-slot>
            <x-slot name="description">
                Pelanggaran santri (aturan R2) → template WhatsApp → antrean job → Web sidecar → nomor wali santri.
            </x-slot>

            <div style="font-size:.85rem;display:flex;flex-direction:column;gap:.35rem;color:var(--fi-color-gray-500);">
                <span>1. Konfigurasi template notifikasi di menu <strong>Templat WhatsApp</strong>.</span>
                <span>2. Cek status pengiriman &amp; kirim ulang (retry) di <strong>Log Notifikasi</strong>.</span>
                <span>3. Pastikan queue worker berjalan: <code style="font-family:monospace;">php artisan queue:work</code>.</span>
                <span>4. Untuk menerima pesan masuk sebagai event Laravel, jalankan daemon: <code style="font-family:monospace;">php artisan whatsapp:web:listen &lt;session&gt;</code> (pakai Supervisor di produksi).</span>
            </div>
        </x-filament::section>
    </div>

    <script>
        function whatsappGateway(sessionId) {
            return {
                sessionId: sessionId,
                phoneNumber: '',
                state: { status: 'loading', qr: null, pairingCode: null, error: null },
                source: null,

                init() {
                    this.refresh();
                    window.addEventListener('wa-refresh', () => this.refresh());
                },

                refresh() {
                    this.closeSource();
                    fetch(`/whatsapp/state/${encodeURIComponent(this.sessionId)}`)
                        .then((r) => r.json())
                        .then((data) => {
                            if (['sidecar_down', 'session_not_found', 'error'].includes(data.status)) {
                                this.state = { status: data.status, qr: null, pairingCode: null, error: data.error };
                                return;
                            }
                            this.state = data;
                            this.openSource();
                        })
                        .catch(() => {
                            this.state = { status: 'sidecar_down', qr: null, pairingCode: null, error: 'Tidak dapat menghubungi server.' };
                        });
                },

                openSource() {
                    if (this.source) return;
                    const es = new EventSource(`/whatsapp/sse/${encodeURIComponent(this.sessionId)}`);
                    this.source = es;
                    const on = (name, fn) => es.addEventListener(name, (e) => {
                        try { fn(e); } catch (_) {}
                    });
                    on('qr', (e) => {
                        const d = JSON.parse(e.data);
                        this.state.status = 'qr';
                        this.state.qr = d.dataUri;
                    });
                    on('code', (e) => {
                        const d = JSON.parse(e.data);
                        this.state.status = 'qr';
                        this.state.pairingCode = d.code;
                    });
                    on('authenticated', () => { this.state.status = 'authenticated'; });
                    on('ready', () => { this.state.status = 'ready'; });
                    on('disconnected', () => { this.state.status = 'disconnected'; });
                    on('auth_failure', () => { this.state.status = 'auth_failure'; });
                    on('error', () => { this.state.status = 'error'; });
                    on('sidecar_down', () => { this.state.status = 'sidecar_down'; this.closeSource(); });
                    es.onerror = () => {}; // EventSource auto-reconnect bila koneksi putus
                },

                closeSource() {
                    if (this.source) {
                        this.source.close();
                        this.source = null;
                    }
                },

                statusLabel(status) {
                    const labels = {
                        loading: 'Memuat…',
                        sidecar_down: 'Sidecar tidak berjalan',
                        session_not_found: 'Sesi belum dimulai',
                        initializing: 'Menyiapkan browser…',
                        qr: 'Menunggu pemindaian / pairing',
                        authenticated: 'Terautentikasi — memuat chat…',
                        ready: 'Siap (terhubung)',
                        disconnected: 'Terputus',
                        auth_failure: 'Gagal autentikasi',
                        error: 'Error',
                    };
                    return labels[status] || status;
                },

                badgeColor(status) {
                    const colors = {
                        ready: '#16a34a',
                        qr: '#d97706',
                        authenticated: '#2563eb',
                        disconnected: '#dc2626',
                        auth_failure: '#dc2626',
                        error: '#dc2626',
                        sidecar_down: '#dc2626',
                        session_not_found: '#6b7280',
                        loading: '#6b7280',
                        initializing: '#6b7280',
                    };
                    return colors[status] || '#6b7280';
                },
            };
        }
    </script>
</x-filament-panels::page>
