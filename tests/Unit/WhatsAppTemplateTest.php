<?php

namespace Tests\Unit;

use App\Models\WhatsAppTemplate;
use PHPUnit\Framework\TestCase;

class WhatsAppTemplateTest extends TestCase
{
    public function test_render_mengganti_semua_placeholder(): void
    {
        $template = new WhatsAppTemplate([
            'pesan' => 'Halo {nama_wali}, santri {nama_santri} melanggar "{nama_kategori}" (+{poin} poin). Total: {total_poin}.',
        ]);

        $result = $template->render([
            'nama_wali' => 'Bapak Ahmad',
            'nama_santri' => 'Muhammad Rizki',
            'nama_kategori' => 'Terlambat',
            'poin' => 5,
            'total_poin' => 55,
        ]);

        $this->assertSame(
            'Halo Bapak Ahmad, santri Muhammad Rizki melanggar "Terlambat" (+5 poin). Total: 55.',
            $result
        );
    }

    public function test_render_placeholder_yang_tidak_diisi_tetap_utuh(): void
    {
        $template = new WhatsAppTemplate(['pesan' => 'Halo {nama_wali}, total poin: {total_poin}.']);

        $this->assertSame(
            'Halo , total poin: {total_poin}.',
            $template->render(['nama_wali' => null])
        );
    }
}
