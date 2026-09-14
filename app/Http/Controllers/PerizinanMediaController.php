<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\User;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PerizinanMediaController extends Controller
{
    /**
     * Tampilkan berkas perizinan secara inline di browser (untuk preview gambar / PDF).
     */
    public function preview(Media $media): BinaryFileResponse
    {
        $this->authorizeAccess($media);

        $path = $this->resolvePath($media);

        $mime = (! empty($media->mime_type) && $media->mime_type !== 'application/x-empty')
            ? $media->mime_type
            : (mime_content_type($path) ?: 'application/octet-stream');

        $response = response()->file($path, [
            'Content-Disposition' => 'inline; filename="' . addslashes($media->file_name) . '"',
            'Cache-Control' => 'private, max-age=86400',
        ]);

        if (! empty($mime) && $mime !== 'application/x-empty') {
            $response->headers->set('Content-Type', $mime);
        }

        return $response;
    }

    /**
     * Unduh berkas perizinan sebagai attachment.
     */
    public function download(Media $media): BinaryFileResponse
    {
        $this->authorizeAccess($media);

        $path = $this->resolvePath($media);

        $mime = (! empty($media->mime_type) && $media->mime_type !== 'application/x-empty')
            ? $media->mime_type
            : (mime_content_type($path) ?: 'application/octet-stream');

        return response()->download($path, $media->file_name, [
            'Content-Type' => $mime,
        ]);
    }

    /**
     * Cari lokasi fisik berkas dengan dukungan fallback multi-disk.
     */
    protected function resolvePath(Media $media): string
    {
        $candidates = [
            $media->getPath(),
            storage_path('app/public/' . $media->id . '/' . $media->file_name),
            storage_path('app/private/' . $media->id . '/' . $media->file_name),
            storage_path('app/' . $media->id . '/' . $media->file_name),
            public_path('storage/' . $media->id . '/' . $media->file_name),
        ];

        foreach ($candidates as $path) {
            if (! empty($path) && file_exists($path)) {
                return $path;
            }
        }

        abort(404, 'Berkas dokumen perizinan tidak ditemukan di server penyimpanan.');
    }

    /**
     * Validasi hak akses pengguna terhadap berkas perizinan.
     */
    protected function authorizeAccess(Media $media): void
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            abort(401, 'Silakan login terlebih dahulu untuk mengakses berkas.');
        }

        // Staff pondok (Admin, Pengurus, Keamanan, Super Admin, dll) selalu diizinkan
        if (
            $user->hasRole(['super_admin', 'Admin', 'Pengurus', 'Keamanan', 'Bendahara']) ||
            $user->can('viewAny', Perizinan::class)
        ) {
            return;
        }

        // Jika model terkait adalah Perizinan
        $perizinan = $media->model;
        if ($perizinan instanceof Perizinan) {
            // Jika user adalah wali santri yang bersangkutan
            $wali = $user->waliSantri;
            if ($wali) {
                $santriIds = $wali->santri()->pluck('santri.id')->toArray();
                if (in_array($perizinan->santri_id, $santriIds, true)) {
                    return;
                }
            }

            // Jika santri pemilik izin terhubung ke user ini
            if ($perizinan->santri && $perizinan->santri->user_id === $user->id) {
                return;
            }
        }

        abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk melihat dokumen ini.');
    }
}
