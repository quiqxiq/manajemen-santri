<?php

namespace Database\Factories;

use App\Models\Kamar;
use Illuminate\Database\Eloquent\Factories\Factory;

class KamarFactory extends Factory
{
    protected $model = Kamar::class;

    public function definition(): array
    {
        return [
            'nama_kamar' => 'Kamar ' . $this->faker->unique()->word(),
            'kapasitas' => $this->faker->numberBetween(10, 30),
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
