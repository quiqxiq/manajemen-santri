/**
 * Landing page — Pondok Pesantren Miftahul Ihsan
 * Gerak & interaksi per PRD 8.6 (secukupnya, hormati prefers-reduced-motion).
 */

const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---------- Navbar: state saat scroll (PRD 7.1, sticky) ---------- */
const nav = document.getElementById('site-nav');

function updateNav() {
    if (!nav) return;
    nav.classList.toggle('is-scrolled', window.scrollY > 12);
}

window.addEventListener('scroll', updateNav, { passive: true });
updateNav();

/* ---------- Menu mobile (hamburger) ---------- */
const navToggle = document.getElementById('nav-toggle');
const mobileMenu = document.getElementById('mobile-menu');

if (navToggle && mobileMenu) {
    navToggle.addEventListener('click', () => {
        const open = mobileMenu.classList.toggle('hidden');
        navToggle.setAttribute('aria-expanded', String(open));
        navToggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
    });

    // Tutup menu saat tautan diklik (anchor scroll)
    mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            navToggle.setAttribute('aria-expanded', 'false');
        });
    });
}

/* ---------- Scroll reveal (PRD 8.6: fade-in saat section masuk viewport) ---------- */
const revealObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
);

document.querySelectorAll('.reveal').forEach((el) => revealObserver.observe(el));

/* ---------- Angka statistik hero: count-up saat terlihat ---------- */
const counters = document.querySelectorAll('[data-count]');

const countObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            countObserver.unobserve(el);

            const target = parseInt(el.dataset.count, 10);
            if (prefersReduced || Number.isNaN(target)) {
                el.textContent = target;
                return;
            }

            const duration = 1200;
            const start = performance.now();

            const step = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
                el.textContent = Math.round(target * eased);
                if (progress < 1) requestAnimationFrame(step);
            };

            requestAnimationFrame(step);
        });
    },
    { threshold: 0.6 }
);

counters.forEach((el) => countObserver.observe(el));

/* ---------- Tahun berjalan di footer ---------- */
const yearEl = document.getElementById('year');
if (yearEl) yearEl.textContent = new Date().getFullYear();
