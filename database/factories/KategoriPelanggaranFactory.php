<?php

namespace Database\Factories;

use App\Models\KategoriPelanggaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriPelanggaranFactory extends Factory
{
    protected $model = KategoriPelanggaran::class;

    public function definition(): array
    {
        return [
            'nama_kategori' => $this->faker->sentence(3),
            'poin' => $this->faker->randomElement([5, 10, 15, 25, 50]),
        ];
    }
}
