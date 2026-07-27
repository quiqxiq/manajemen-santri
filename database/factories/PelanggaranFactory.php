<?php

namespace Database\Factories;

use App\Models\KategoriPelanggaran;
use App\Models\Pelanggaran;
use App\Models\Pengurus;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelanggaranFactory extends Factory
{
    protected $model = Pelanggaran::class;

    public function definition(): array
    {
        return [
            'santri_id' => Santri::factory(),
            'kategori_pelanggaran_id' => KategoriPelanggaran::factory(),
            'pengurus_id' => Pengurus::factory(),
            'deskripsi' => $this->faker->sentence(),
            'tanggal_kejadian' => $this->faker->date(),
            'poin' => 10,
            'status' => 'normal',
        ];
    }
}
