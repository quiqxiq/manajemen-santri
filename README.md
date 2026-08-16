# Sistem Manajemen Santri — PP. Miftahul Ihsan

Sistem informasi terpadu untuk Pondok Pesantren Miftahul Ihsan, Errabu, Bluto, Sumenep: manajemen santri,
wali santri, kamar, pelanggaran, tagihan & pembayaran, tahfidz, perizinan, kesehatan, serta **notifikasi
WhatsApp otomatis** untuk pelanggaran dan tagihan.

- **Panel Admin** (`/admin`) — staf: Admin, Operator, Pengasuh (read-only), Keamanan.
- **Panel Wali Santri** (`/wali`) — orang tua melihat data anaknya sendiri; login pakai **nomor HP** + password.
- **Landing Page** (`/`) — profil/portofolio pesantren (desain mengikuti `prd.md`).

## Teknologi

| Komponen | Versi / Keterangan |
|---|---|
| PHP | `^8.3` |
| Laravel | `^13.8` |
| Filament | `^5.7` (admin + wali panel) |
| Database | MySQL (`^8.0`, mis. Laragon) |
| Frontend | Tailwind CSS v4 + Vite |
| RBAC | `spatie/laravel-permission` `^8.3` + `bezhansalleh/filament-shield` `^4.3` |
| Media | `spatie/laravel-medialibrary` `^11.23` + plugin Filament |
| WhatsApp | `kstmostofa/laravel-whatsapp` (Web sidecar berbasis whatsapp-web.js, mode headless) |
| Queue | `database` (`QUEUE_CONNECTION=database`) |

## Persyaratan

- PHP **8.3+** (ekstensi: `gd`, `pdo_mysql`, `zip`, `fileinfo`, `intl`)
- Composer
- Node.js **18+** & npm (untuk asset Vite dan sidecar WhatsApp)
- MySQL 8.x (Laragon / XAMPP) — sudah berjalan
- Git Bash / terminal (perintah di bawah memakai sintaks bash)

> Untuk WhatsApp sidecar: proses `whatsapp:sidecar:install` mengunduh **Chromium (~600 MB)** sekali saja.
> whatsapp-web.js memakai browser automation — cocok untuk volume notifikasi wajar; untuk skala
> besar/bisnis gunakan Meta Cloud API resmi.

## Instalasi

```bash
# 1. Clone & install dependensi
git clone <repo-url> manajemen-santri
cd manajemen-santri
composer install
npm install

# 2. Konfigurasi environment
cp .env.example .env
php artisan key:generate
```

### 3. Konfigurasi `.env`

Database **MySQL** (Laragon default: user `root`, tanpa password):

```dotenv
APP_NAME="PP. Miftahul Ihsan"
APP_URL=http://localhost:8000
APP_LOCALE=id
APP_FAKER_LOCALE=id_ID

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manajemen_santri
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database
```

WhatsApp (nilai default sudah sesuai — cukup isi token):

```dotenv
WHATSAPP_WEB_ENABLED=true
WHATSAPP_WEB_TOKEN=<token acak>                 # php -r "echo bin2hex(random_bytes(32));"
WHATSAPP_WEB_HOST=127.0.0.1
WHATSAPP_WEB_PORT=3000
WHATSAPP_WEB_SIDECAR_PATH=whatsapp-sidecar
WHATSAPP_UI_ENABLED=false                       # headless: UI ada di dalam Filament
WHATSAPP_WEBHOOK_ENABLED=false                  # webhook Cloud API tidak dipakai
```

> ⚠️ Pastikan tidak ada variabel env `DB_CONNECTION=sqlite` yang ter-export di terminal Anda
> (`unset DB_CONNECTION` bila ada) — variabel env menimpa `.env`.

### 4. Buat database

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS manajemen_santri CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 5. Migrasi + permission + seed (urutan penting!)

```bash
php artisan migrate

# Permission dibuat oleh filament-shield (bukan dari seeder) — jalankan dulu:
php artisan shield:generate --all --panel=admin --option=permissions -n

# Seed: role + permission per role, user demo, 56 santri + 5 kamar + 56 akun wali (dari Excel),
# template WhatsApp default, pengaturan rule poin.
php artisan db:seed
```

> `RoleAndPermissionSeeder` memberi role **Admin** akses ke **semua** permission yang ada saat
> itu. Setelah menambah resource/page baru, jalankan ulang:
>
> ```bash
> php artisan shield:generate --all --panel=admin --option=permissions -n
> php artisan db:seed --class=RoleAndPermissionSeeder
> ```

### 6. Asset frontend

```bash
npm run build        # produksi
# atau saat pengembangan:
npm run dev          # dev server Vite (membuat public/hot)
```

### 7. Jalankan aplikasi

```bash
php artisan serve    # http://localhost:8000
```

## Akun default (password semua: `password`)

| Username | Role | Akses |
|---|---|---|
| `admin` | Admin | Panel admin penuh (207 permission) |
| `tatausaha`, `keuangan`, `ustadz` | Operator | CRUD data operasional |
| `pengasuh` | Pengasuh | Read-only semua data |
| `keamanan` | Keamanan | Pelanggaran & perizinan |
| `wali` | Wali Santri | Panel wali (demo) |
| `<nomor HP wali>` | Wali Santri | Panel wali — mis. `087787224620` |

> Ganti password setelah login pertama.

## Notifikasi WhatsApp

Notifikasi ditangani paket [`kstmostofa/laravel-whatsapp`](https://github.com/kstmostofa/laravel-whatsapp)
(mode headless — UI admin dibangun di dalam Filament) memakai **Web sidecar** (whatsapp-web.js) untuk
nomor pribadi, lengkap dengan scan QR **dan** kode pairing.

### Instalasi sidecar (sekali jalan)

```bash
# Salin sidecar ke whatsapp-sidecar/ (sudah dimodifikasi: dukungan kode pairing)
php artisan vendor:publish --tag=laravel-whatsapp-sidecar

# npm ci + unduh Chromium ~600 MB (sekali saja)
php artisan whatsapp:sidecar:install
```

### Menjalankan sidecar & queue

```bash
# 1. Sidecar Node di 127.0.0.1:3000 (proses background terpisah)
php artisan whatsapp:sidecar:start

# Cek status: terinstall? jalan? sesi aktif?
php artisan whatsapp:sidecar:status

# 2. Worker queue untuk eksekusi job pengiriman notifikasi
php artisan queue:work

# 3. (Opsional) event pesan masuk — pakai Supervisor di produksi
php artisan whatsapp:web:listen main
```

Perintah lain: `php artisan whatsapp:sidecar:stop`, `php artisan whatsapp:health`
(kesehatan sidecar & backend), `php artisan whatsapp:web:listen <sesi>`.

### Menggunakan dari panel admin (`/admin`)

- **WhatsApp Gateway** (`/admin/whatsapp-gateway`) — mulai sesi, pindai QR atau minta **kode pairing**
  (nomor format internasional tanpa `+`), status sesi tampil **live via SSE** (tanpa polling),
  hentikan/hapus sesi.
- **Templat WhatsApp** — kelola isi pesan notifikasi dengan placeholder `{nama_santri}`,
  `{nama_kategori}`, `{poin}`, `{total_poin}`, `{nama_wali}`. Template default:
  `pelanggaran_peringatan` dan `tagihan`.
- **Log Notifikasi WA Bot** — riwayat pengiriman; tombol **Kirim Ulang** (retry) untuk log berstatus
  `failed`/`pending`.

**Alur notifikasi:**

1. **Pelanggaran** → total poin santri ≥ `poin_peringatan` (pengaturan rule poin) → `KedisiplinanService`
   merender template `pelanggaran_peringatan`.
2. **Tagihan** → saat tagihan dibuat di resource Tagihan → `TagihanService` merender template `tagihan`
   dan mengirim ke wali (hook `after()` di halaman Manage Tagihan).
3. Job `KirimNotifikasiWhatsApp` (queue, retry otomatis 3× dengan backoff) → Web sidecar → nomor HP wali
   santri → dicatat di `notifikasi_log` (bisa di-retry manual dari Log Notifikasi).

## Panel, Role & Permission

Permission dibentuk `shield:generate` per entitas (resource/page/widget) dengan format `ViewAny:Entitas`,
`View:Entitas`, `Create:Entitas`, dst. Pembagian per role (di `RoleAndPermissionSeeder`):

| Role | Keterangan |
|---|---|
| **Admin** | Semua permission (207) — termasuk User & WhatsApp |
| **Operator** | CRUD data operasional (santri, tagihan, pelanggaran, tahfidz, dst.) |
| **Pengasuh** | Hanya baca (View/ViewAny) semua data |
| **Keamanan** | Pelanggaran, kategori pelanggaran, perizinan (CRUD) |
| **Wali Santri** | Hanya data anak sendiri (di-scope di panel wali) |

Akses portal diatur `User::canAccessPanel()`: staf → panel admin, wali santri → panel wali.

## Struktur penting

```
app/Filament/            # Resource & halaman panel admin + panel wali (app/Filament/Wali)
app/Providers/Filament/  # AdminPanelProvider, WaliPanelProvider
app/Services/            # KedisiplinanService, TagihanService, WhatsAppNotificationService
app/Settings/            # WhatsAppSettings, KedisiplinanSettings (spatie/laravel-settings)
database/seeders/        # RoleAndPermissionSeeder, SantriMiftahulIhsanSeeder, WaliSantriMiftahulIhsanSeeder
resources/views/landing/ # Landing page: index + partials/ (head, header, footer, icons) + sections/
whatsapp-sidecar/        # Web sidecar (whatsapp-web.js) — hasil vendor:publish
```

## Troubleshooting

| Masalah | Solusi |
|---|---|
| Login gagal "credentials do not match" | Data user hilang (DB pernah di-fresh). Jalankan ulang: `php artisan migrate`, `shield:generate ...`, `db:seed` (lihat langkah 5) |
| Menu tidak muncul di panel admin | Permission belum tersync. Jalankan `php artisan shield:generate --all --panel=admin --option=permissions -n` lalu `php artisan db:seed --class=RoleAndPermissionSeeder` |
| Notifikasi tidak terkirim | Cek `php artisan whatsapp:sidecar:status` (sidecar jalan?), `php artisan queue:work` (worker jalan?), status log di **Log Notifikasi WA Bot** |
| Halaman tanpa CSS/JS | Hapus file basi `public/hot` bila server Vite tidak berjalan (`npm run dev` membuatnya lagi) |
| Sidecar gagal start | Jalankan ulang `php artisan whatsapp:sidecar:install` (unduh Chromium) dan pastikan port 3000 bebas |

## Lisensi

Proyek ini dikembangkan untuk Yayasan Miftahul Ihsan (YASMI). Framework yang dipakai berlisensi MIT
(Laravel, Filament, dan paket pendukung lainnya).
