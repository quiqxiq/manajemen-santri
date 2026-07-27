<?php

namespace Database\Factories;

use App\Models\Pengurus;
use App\Models\RiwayatKesehatan;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiwayatKesehatanFactory extends Factory
{
    protected $model = RiwayatKesehatan::class;

    public function definition(): array
    {
        return [
            'santri_id' => Santri::factory(),
            'pengurus_id' => Pengurus::factory(),
            'tanggal_kejadian' => $this->faker->date(),
            'keluhan' => $this->faker->sentence(),
            'suhu_tubuh' => $this->faker->randomFloat(1, 36, 39),
            'diagnosis_sementara' => $this->faker->sentence(),
            'tindakan' => $this->faker->randomElement(['istirahat_kamar', 'pemberian_obat', 'mini_puskesmas', 'rujuk_rs']),
            'tujuan_rujukan' => null,
            'status' => 'dalam_perawatan',
        ];
    }
}
