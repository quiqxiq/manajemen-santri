<!DOCTYPE html>
<html lang="id">

@include('landing.partials.head')

<body class="bg-kitab text-pegon font-body antialiased">

<a href="#beranda" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[100] focus:rounded-md focus:bg-sepuh focus:px-4 focus:py-2 focus:text-white">
    Lewati ke konten
</a>

@include('landing.partials.icons')

@include('landing.partials.header')

<main id="beranda" class="scroll-mt-24">
    @include('landing.sections.hero')
    @include('landing.sections.pengasuh')
    @include('landing.sections.sejarah')
    @include('landing.sections.visi-misi')
    @include('landing.sections.keunggulan')
    @include('landing.sections.unit-pendidikan')
    @include('landing.sections.kurikulum')
    @include('landing.sections.layanan')
    @include('landing.sections.fasilitas')
    @include('landing.sections.prestasi')
    @include('landing.sections.galeri')
    @include('landing.sections.testimoni')
    @include('landing.sections.berita')
    @include('landing.sections.faq')
    @include('landing.sections.kontak')
</main>

@include('landing.partials.footer')

</body>
</html>
