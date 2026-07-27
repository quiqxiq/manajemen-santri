<?php

namespace Database\Factories;

use App\Models\Pengurus;
use App\Models\Santri;
use App\Models\Tahfidz;
use Illuminate\Database\Eloquent\Factories\Factory;

class TahfidzFactory extends Factory
{
    protected $model = Tahfidz::class;

    public function definition(): array
    {
        return [
            'santri_id' => Santri::factory(),
            'pengurus_id' => Pengurus::factory(),
            'jenis' => $this->faker->randomElement(['setoran', 'murojaah']),
            'surat' => $this->faker->randomElement(['An-Naba', 'An-Naziat', 'Abasa', 'At-Takwir', 'Al-Infitar', 'Al-Mutaffifin']),
            'juz' => 30,
            'ayat_dari' => 1,
            'ayat_sampai' => 15,
            'status' => $this->faker->randomElement(['lulus', 'tidak_lulus']),
            'catatan' => $this->faker->sentence(),
            'tanggal' => $this->faker->date(),
        ];
    }
}
