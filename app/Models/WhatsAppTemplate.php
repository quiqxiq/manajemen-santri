<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppTemplate extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_templates';

    protected $fillable = [
        'nama',
        'judul',
        'pesan',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    /**
     * Render body template, mengganti token {placeholder} dengan nilai data.
     *
     * @param  array<string, mixed>  $data
     */
    public function render(array $data = []): string
    {
        $pesan = $this->pesan;

        foreach ($data as $key => $value) {
            $pesan = str_replace('{'.$key.'}', (string) $value, $pesan);
        }

        return $pesan;
    }
}
