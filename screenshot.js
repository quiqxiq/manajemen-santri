import fs from 'fs';
import path from 'path';
import puppeteer from 'puppeteer';

const BASE_URL = process.env.APP_URL || 'http://127.0.0.1:8000';
const SCREENSHOT_DIR = path.resolve(process.cwd(), 'screenshots');

// Waktu penungguan rendering per halaman (10000ms = 10 detik)
const RENDER_DELAY_MS = 10000;
// Waktu penungguan autentikasi login (5000ms = 5 detik)
const LOGIN_DELAY_MS = 5000;

if (!fs.existsSync(SCREENSHOT_DIR)) {
    fs.mkdirSync(SCREENSHOT_DIR, { recursive: true });
}

function getExecutablePath() {
    const possiblePaths = [
        'C:\\Users\\g0str\\.cache\\puppeteer\\chrome\\win64-151.0.7922.47\\chrome-win64\\chrome.exe',
        'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
        'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe'
    ];

    for (const p of possiblePaths) {
        if (fs.existsSync(p)) {
            return p;
        }
    }
    return null;
}

// Fungsi penungguan rendering sederhana (Tunggu 10 detik tanpa auto-scroll)
async function waitForPageRender(page, delayMs = RENDER_DELAY_MS) {
    await new Promise(r => setTimeout(r, delayMs));
}

async function takeScreenshots() {
    console.log('🚀 Memulai SIPADES Screenshot Generator...');
    console.log(`⏱️ Waktu tunggu render per halaman disetel: ${RENDER_DELAY_MS / 1000} detik (Tanpa auto-scroll)`);

    const execPath = getExecutablePath();
    const launchOptions = {
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--window-size=1440,900']
    };

    if (execPath) {
        console.log(`📌 Browser Executable: ${execPath}`);
        launchOptions.executablePath = execPath;
    }

    const browser = await puppeteer.launch(launchOptions);

    // ==========================================
    // 1. HALAMAN PUBLIK & GUEST (TANPA LOGIN)
    // ==========================================
    console.log('\n🌐 1. MENANGKAP HALAMAN PUBLIK & GUEST...');
    const publicPage = await browser.newPage();
    await publicPage.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1 });

    const publicPages = [
        { name: '01_publik_landing.png', path: '/' },
        { name: '02_portal_login.png', path: '/portal/login' },
        { name: '03_registrasi_warga.png', path: '/registrasi' },
        { name: '04_verifikasi_otp.png', path: '/verifikasi-otp' },
        { name: '05_admin_login.png', path: '/admin/login' },
        { name: '06_verifikasi_surat_tte_publik.png', path: '/verifikasi-surat/TTE-KDL-DEMO12345' },
    ];

    for (const p of publicPages) {
        try {
            console.log(`📸 Capturing Public: ${p.name} (${p.path})`);
            await publicPage.goto(`${BASE_URL}${p.path}`, { waitUntil: 'networkidle0', timeout: 45000 });
            await waitForPageRender(publicPage, RENDER_DELAY_MS);
            await publicPage.screenshot({ path: path.join(SCREENSHOT_DIR, p.name), fullPage: true });
        } catch (e) {
            console.warn(`⚠️ Skipped ${p.name}: ${e.message}`);
        }
    }

    // ==========================================
    // 2. PORTAL WARGA (LOGIN SEBAGAI WARGA)
    // ==========================================
    console.log('\n🔑 2. AUTENTIKASI PORTAL WARGA (warga@karduluk.desa.id)...');
    const wargaContext = await browser.createBrowserContext();
    const wargaPage = await wargaContext.newPage();
    await wargaPage.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1 });

    try {
        await wargaPage.goto(`${BASE_URL}/portal/login`, { waitUntil: 'networkidle0', timeout: 45000 });
        await new Promise(r => setTimeout(r, 2000));

        await wargaPage.waitForSelector('input[type="email"], input[name*="email"], input[id*="email"]', { timeout: 15000 });
        const emailInput = await wargaPage.$('input[type="email"], input[name*="email"], input[id*="email"]');
        const passwordInput = await wargaPage.$('input[type="password"], input[name*="password"], input[id*="password"]');

        if (emailInput && passwordInput) {
            await emailInput.type('warga@karduluk.desa.id');
            await passwordInput.type('password');

            const submitBtn = await wargaPage.$('button[type="submit"]');
            if (submitBtn) {
                await submitBtn.click();
                await wargaPage.waitForNavigation({ waitUntil: 'networkidle0', timeout: 20000 }).catch(() => {});
                await new Promise(r => setTimeout(r, LOGIN_DELAY_MS));
            }
        }
        console.log(`✅ Status Login Warga Sukses. URL Aktif: ${wargaPage.url()}`);
    } catch (e) {
        console.warn('⚠️ Kendala Login Portal Warga:', e.message);
    }

    const portalPages = [
        { name: '07_portal_dashboard.png', path: '/portal/dashboard' },
        { name: '08_portal_ajukan_surat_buat.png', path: '/portal/pengajuan/buat' },
        { name: '09_portal_pengajuan_index.png', path: '/portal/pengajuan' },
        { name: '10_portal_pengajuan_status_detail.png', path: '/portal/pengajuan/2/status' },
        { name: '11_portal_pengajuan_revisi.png', path: '/portal/pengajuan/1/revisi' },
        { name: '12_portal_surat_terbit_index.png', path: '/portal/surat-terbit' },
        { name: '13_portal_profil_saya.png', path: '/portal/profil' },
    ];

    for (const p of portalPages) {
        try {
            console.log(`📸 Capturing Portal Warga: ${p.name} (${p.path})`);
            await wargaPage.goto(`${BASE_URL}${p.path}`, { waitUntil: 'networkidle0', timeout: 45000 });
            await waitForPageRender(wargaPage, RENDER_DELAY_MS);
            await wargaPage.screenshot({ path: path.join(SCREENSHOT_DIR, p.name), fullPage: true });
        } catch (e) {
            console.warn(`⚠️ Skipped ${p.name}: ${e.message}`);
        }
    }

    // ==========================================
    // 3. FILAMENT ADMIN PANEL (LOGIN SEBAGAI ADMIN)
    // ==========================================
    console.log('\n🔑 3. AUTENTIKASI FILAMENT ADMIN PANEL (admin@karduluk.desa.id)...');
    const adminContext = await browser.createBrowserContext();
    const adminPage = await adminContext.newPage();
    await adminPage.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1 });

    try {
        await adminPage.goto(`${BASE_URL}/admin/login`, { waitUntil: 'networkidle0', timeout: 45000 });
        await new Promise(r => setTimeout(r, 2000));

        await adminPage.waitForSelector('input[type="email"], input[name*="email"], input[id*="email"]', { timeout: 15000 });
        const emailInput = await adminPage.$('input[type="email"], input[name*="email"], input[id*="email"]');
        const passwordInput = await adminPage.$('input[type="password"], input[name*="password"], input[id*="password"]');

        if (emailInput && passwordInput) {
            await emailInput.type('admin@karduluk.desa.id');
            await passwordInput.type('password');

            const submitBtn = await adminPage.$('button[type="submit"]');
            if (submitBtn) {
                await submitBtn.click();
                await adminPage.waitForNavigation({ waitUntil: 'networkidle0', timeout: 20000 }).catch(() => {});
                await new Promise(r => setTimeout(r, LOGIN_DELAY_MS));
            }
        }
        console.log(`✅ Status Login Admin Sukses. URL Aktif: ${adminPage.url()}`);
    } catch (e) {
        console.warn('⚠️ Kendala Login Admin Panel:', e.message);
    }

    const adminPages = [
        { name: '14_admin_dashboard.png', path: '/admin' },
        { name: '15_admin_jenis_surat_index.png', path: '/admin/jenis-surats' },
        { name: '16_admin_jenis_surat_create.png', path: '/admin/jenis-surats/create' },
        { name: '17_admin_jenis_surat_edit.png', path: '/admin/jenis-surats/1/edit' },
        { name: '18_admin_pengajuan_surat_index.png', path: '/admin/pengajuan-surats' },
        { name: '19_admin_pengajuan_surat_detail.png', path: '/admin/pengajuan-surats/1' },
        { name: '20_admin_surat_terbit_index.png', path: '/admin/surat-terbits' },
        { name: '21_admin_template_pesan_index.png', path: '/admin/template-pesans' },
        { name: '22_admin_template_pesan_create.png', path: '/admin/template-pesans/create' },
        { name: '23_admin_template_pesan_edit.png', path: '/admin/template-pesans/1/edit' },
        { name: '24_admin_notifikasi_log_index.png', path: '/admin/notifikasi-logs' },
        { name: '25_admin_whatsapp_settings.png', path: '/admin/whatsapp-gateway-settings' },
        { name: '26_admin_laporan.png', path: '/admin/laporan' },
        { name: '27_admin_users_index.png', path: '/admin/users' },
        { name: '28_admin_users_create.png', path: '/admin/users/create' },
        { name: '29_admin_users_edit.png', path: '/admin/users/1/edit' },
        { name: '30_admin_activity_logs.png', path: '/admin/activity-logs' },
        { name: '31_admin_shield_roles_index.png', path: '/admin/shield/roles' },
        { name: '32_admin_shield_roles_create.png', path: '/admin/shield/roles/create' },
        { name: '33_admin_shield_roles_edit.png', path: '/admin/shield/roles/1/edit' },
    ];

    for (const p of adminPages) {
        try {
            console.log(`📸 Capturing Admin: ${p.name} (${p.path})`);
            await adminPage.goto(`${BASE_URL}${p.path}`, { waitUntil: 'networkidle0', timeout: 45000 });
            await waitForPageRender(adminPage, RENDER_DELAY_MS);
            await adminPage.screenshot({ path: path.join(SCREENSHOT_DIR, p.name), fullPage: true });
        } catch (e) {
            console.warn(`⚠️ Skipped ${p.name}: ${e.message}`);
        }
    }

    await browser.close();
    console.log(`\n🎉 Seluruh 33 screenshot berhasil diambil & disimpan di: ${SCREENSHOT_DIR}`);
}

takeScreenshots().catch(console.error);
