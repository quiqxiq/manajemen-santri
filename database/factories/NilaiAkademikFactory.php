<?php

namespace Database\Factories;

use App\Models\MataPelajaran;
use App\Models\NilaiAkademik;
use App\Models\Pengurus;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

class NilaiAkademikFactory extends Factory
{
    protected $model = NilaiAkademik::class;

    public function definition(): array
    {
        return [
            'santri_id' => Santri::factory(),
            'mata_pelajaran_id' => MataPelajaran::factory(),
            'pengurus_id' => Pengurus::factory(),
            'semester' => $this->faker->randomElement([1, 2]),
            'tahun_ajaran' => '2025/2026',
            'nilai' => $this->faker->randomFloat(2, 60, 100),
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
