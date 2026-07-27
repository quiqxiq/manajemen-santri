<?php

namespace Database\Factories;

use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagihanFactory extends Factory
{
    protected $model = Tagihan::class;

    public function definition(): array
    {
        return [
            'santri_id' => Santri::factory(),
            'jenis' => 'spp',
            'bulan' => $this->faker->numberBetween(1, 12),
            'tahun' => 2026,
            'nominal' => 500000.00,
            'status' => 'belum_lunas',
            'jatuh_tempo' => $this->faker->date(),
        ];
    }
}
