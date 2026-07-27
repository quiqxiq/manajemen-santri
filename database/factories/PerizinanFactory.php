<?php

namespace Database\Factories;

use App\Models\Perizinan;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerizinanFactory extends Factory
{
    protected $model = Perizinan::class;

    public function definition(): array
    {
        $mulai = $this->faker->date();
        return [
            'santri_id' => Santri::factory(),
            'jenis_izin' => $this->faker->randomElement(['pulang', 'sakit', 'acara_keluarga', 'lainnya']),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $this->faker->dateTimeBetween($mulai, '+3 days')->format('Y-m-d'),
            'alasan' => $this->faker->sentence(),
            'status' => 'diajukan',
        ];
    }
}
