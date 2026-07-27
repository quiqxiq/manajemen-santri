<?php

namespace Database\Factories;

use App\Models\Penghargaan;
use App\Models\Pengurus;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenghargaanFactory extends Factory
{
    protected $model = Penghargaan::class;

    public function definition(): array
    {
        return [
            'santri_id' => Santri::factory(),
            'pengurus_id' => Pengurus::factory(),
            'bidang' => $this->faker->randomElement(['akademik', 'tahfidz', 'kedisiplinan', 'lomba']),
            'deskripsi' => $this->faker->sentence(),
            'tanggal' => $this->faker->date(),
        ];
    }
}
