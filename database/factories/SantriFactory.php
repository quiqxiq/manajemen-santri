<?php

namespace Database\Factories;

use App\Models\Kamar;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

class SantriFactory extends Factory
{
    protected $model = Santri::class;

    public function definition(): array
    {
        return [
            'nis' => 'SAN-' . $this->faker->unique()->numberBetween(100000, 999999),
            'nama_lengkap' => $this->faker->name(),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '-12 years'),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'alamat' => $this->faker->address(),
            'asal_sekolah' => 'SMPN ' . $this->faker->numberBetween(1, 10),
            'kamar_id' => Kamar::factory(),
            'status' => 'aktif',
            'tanggal_masuk' => $this->faker->date(),
        ];
    }
}
