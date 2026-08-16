/**
 * Screenshot Generator — Sistem Manajemen Santri PP. Miftahul Ihsan
 * Mengambil screenshot seluruh route: landing page, panel admin, portal wali.
 */
import fs from 'fs';
import path from 'path';
import puppeteer from 'puppeteer-core';

const BASE_URL = process.env.SCREENSHOT_BASE_URL || 'http://127.0.0.1:8000';
const SCREENSHOT_DIR = path.resolve(process.cwd(), 'screenshots');
const RENDER_DELAY_MS = 4000;
const LOGIN_DELAY_MS = 3500;

if (!fs.existsSync(SCREENSHOT_DIR)) {
    fs.mkdirSync(SCREENSHOT_DIR, { recursive: true });
}

function getExecutablePath() {
    const possiblePaths = [
        'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
        'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
    ];
    for (const p of possiblePaths) {
        if (fs.existsSync(p)) {
            return p;
        }
    }
    return null;
}

async function waitForPageRender(page, delayMs = RENDER_DELAY_MS) {
    await new Promise(r => setTimeout(r, delayMs));
}

async function waitForLivewire(page) {
    // Tunggu Livewire ter-hydrate sebelum mengetik (form login Filament = komponen Livewire).
    await page.waitForFunction(() => window.Livewire && window.Livewire.initialized, { timeout: 15000 }).catch(() => {});
    await new Promise(r => setTimeout(r, 1500));
}

async function shot(page, name, p) {
    try {
        console.log(`📸 Capturing: ${name} (${p})`);
        await page.goto(`${BASE_URL}${p}`, { waitUntil: 'domcontentloaded', timeout: 45000 });
        await waitForPageRender(page);
        await page.screenshot({ path: path.join(SCREENSHOT_DIR, name), fullPage: true });
    } catch (e) {
        console.warn(`⚠️ Skipped ${name}: ${e.message}`);
    }
}

async function takeScreenshots() {
    console.log('🚀 Memulai Screenshot Generator Manajemen Santri...');
    const execPath = getExecutablePath();
    const launchOptions = {
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--window-size=1440,900'],
    };
    if (execPath) {
        console.log(`📌 Browser Executable: ${execPath}`);
        launchOptions.executablePath = execPath;
    }

    const browser = await puppeteer.launch(launchOptions);

    // Intercept: batalkan koneksi SSE (stream tak berujung memblokir server dev PHP single-threaded).
    // Halaman WhatsApp tetap render dengan status awal; status live tidak perlu discreenshot.
    const abortSse = async (page) => {
        await page.setRequestInterception(true);
        page.on('request', (req) => {
            if (req.url().includes('/whatsapp/sse/')) {
                req.abort();
            } else {
                req.continue();
            }
        });
    };

    // ==========================================
    // 1. HALAMAN PUBLIK (TANPA LOGIN)
    // ==========================================
    console.log('\n🌐 1. HALAMAN PUBLIK...');
    const publicPage = await browser.newPage();
    await publicPage.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1 });
    await abortSse(publicPage);

    await shot(publicPage, '01_publik_landing.png', '/');
    await shot(publicPage, '02_admin_login.png', '/admin/login');
    await shot(publicPage, '03_wali_login.png', '/wali/login');

    // ==========================================
    // 2. PANEL ADMIN (login: admin / password)
    // ==========================================
    console.log('\n🔑 2. AUTENTIKASI PANEL ADMIN (admin / password)...');
    const adminContext = await browser.createBrowserContext();
    const adminPage = await adminContext.newPage();
    await adminPage.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1 });
    await abortSse(adminPage);

    try {
        await adminPage.goto(`${BASE_URL}/admin/login`, { waitUntil: 'domcontentloaded', timeout: 45000 });
        await adminPage.waitForSelector('input[id="form.username"]', { timeout: 20000 });
        await waitForLivewire(adminPage);
        await adminPage.type('input[id="form.username"]', 'admin');
        await adminPage.type('input[id="form.password"]', 'password');
        await adminPage.click('button[type="submit"]');
        await adminPage.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 20000 }).catch(() => {});
        await new Promise(r => setTimeout(r, LOGIN_DELAY_MS));
        console.log(`✅ Login Admin. URL: ${adminPage.url()}`);
    } catch (e) {
        console.warn('⚠️ Kendala login admin:', e.message);
    }

    const adminPages = [
        { name: '04_admin_dashboard.png', path: '/admin' },
        { name: '05_admin_santris.png', path: '/admin/santris' },
        { name: '06_admin_wali_santris.png', path: '/admin/wali-santris' },
        { name: '07_admin_kamars.png', path: '/admin/kamars' },
        { name: '08_admin_pelanggarans.png', path: '/admin/pelanggarans' },
        { name: '09_admin_penghargaans.png', path: '/admin/penghargaans' },
        { name: '10_admin_tagihans.png', path: '/admin/tagihans' },
        { name: '11_admin_pembayarans.png', path: '/admin/pembayarans' },
        { name: '12_admin_perizinans.png', path: '/admin/perizinans' },
        { name: '13_admin_tahfidzs.png', path: '/admin/tahfidzs' },
        { name: '14_admin_riwayat_kesehatans.png', path: '/admin/riwayat-kesehatans' },
        { name: '15_admin_penguruses.png', path: '/admin/penguruses' },
        { name: '16_admin_users.png', path: '/admin/users' },
        { name: '17_admin_whatsapp_gateway.png', path: '/admin/whatsapp-gateway' },
        { name: '18_admin_whatsapp_templates.png', path: '/admin/whatsapp-templates' },
        { name: '19_admin_notifikasi_logs.png', path: '/admin/notifikasi-logs' },
        { name: '20_admin_roles.png', path: '/admin/shield/roles' },
        { name: '21_admin_rule_poin.png', path: '/admin/manage-kedisiplinan-settings' },
    ];

    for (const p of adminPages) {
        await shot(adminPage, p.name, p.path);
    }

    // ==========================================
    // 3. PORTAL WALI SANTRI (login: nomor HP / password)
    // ==========================================
    console.log('\n🔑 3. AUTENTIKASI PORTAL WALI (087787224620 / password)...');
    const waliContext = await browser.createBrowserContext();
    const waliPage = await waliContext.newPage();
    await waliPage.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1 });
    await abortSse(waliPage);

    try {
        await waliPage.goto(`${BASE_URL}/wali/login`, { waitUntil: 'domcontentloaded', timeout: 45000 });
        await waliPage.waitForSelector('input[id="form.username"]', { timeout: 20000 });
        await waitForLivewire(waliPage);
        await waliPage.type('input[id="form.username"]', '087787224620');
        await waliPage.type('input[id="form.password"]', 'password');
        await waliPage.click('button[type="submit"]');
        await waliPage.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 20000 }).catch(() => {});
        await new Promise(r => setTimeout(r, LOGIN_DELAY_MS));
        console.log(`✅ Login Wali. URL: ${waliPage.url()}`);
    } catch (e) {
        console.warn('⚠️ Kendala login wali:', e.message);
    }

    const waliPages = [
        { name: '22_wali_dashboard.png', path: '/wali' },
        { name: '23_wali_santris.png', path: '/wali/santris' },
        { name: '24_wali_pelanggarans.png', path: '/wali/pelanggarans' },
        { name: '25_wali_tagihans.png', path: '/wali/tagihans' },
        { name: '26_wali_tahfidzs.png', path: '/wali/tahfidzs' },
        { name: '27_wali_perizinans.png', path: '/wali/perizinans' },
    ];

    for (const p of waliPages) {
        await shot(waliPage, p.name, p.path);
    }

    await browser.close();
    console.log(`\n🎉 ${3 + adminPages.length + waliPages.length} screenshot berhasil disimpan di: ${SCREENSHOT_DIR}`);
}

takeScreenshots().catch(console.error);
