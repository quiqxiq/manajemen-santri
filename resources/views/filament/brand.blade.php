{{-- Brand lockup PP. Miftahul Ihsan.
     Inline style dipakai (bukan class Tailwind) karena CSS panel Filament tidak memuat
     build Tailwind aplikasi — class seperti `flex` tidak tersedia di panel. --}}
<div class="brand-lockup" style="display: flex; align-items: center; gap: 0.5rem; white-space: nowrap;">
    <img
        src="{{ asset('images/logo.webp') }}"
        alt="PP. Miftahul Ihsan"
        style="height: 2rem; width: auto; flex-shrink: 0;"
    />
    <span style="font-size: 0.875rem; font-weight: 600; color: inherit; line-height: 1.25;">
        PP. Miftahul Ihsan
    </span>
</div>

<style>
    /* Halaman login: logo di atas, teks di bawah (kolom, rata tengah) */
    .fi-simple-page .brand-lockup {
        flex-direction: column;
        gap: 0.375rem;
        text-align: center;
        white-space: normal;
    }

    .fi-simple-page .brand-lockup span {
        white-space: nowrap;
    }
</style>
