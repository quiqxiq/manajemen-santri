<?php

namespace Database\Factories;

use App\Models\PenyakitBawaan;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenyakitBawaanFactory extends Factory
{
    protected $model = PenyakitBawaan::class;

    public function definition(): array
    {
        return [
            'santri_id' => Santri::factory(),
            'nama_penyakit' => $this->faker->randomElement(['Asma', 'Alergi Dingin', 'Maag Krois', 'Migrain']),
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
