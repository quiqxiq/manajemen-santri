<?php

namespace Database\Factories;

use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class MataPelajaranFactory extends Factory
{
    protected $model = MataPelajaran::class;

    public function definition(): array
    {
        return [
            'nama_mapel' => $this->faker->unique()->randomElement([
                'Fiqih', 'Nahwu', 'Shorof', 'Aqidatul Awam', 'Hadits Arbain', 'Tafsir Jalalain', 'Tajwid', 'Balaaghoh'
            ]),
        ];
    }
}
