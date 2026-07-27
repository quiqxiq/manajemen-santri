<?php

namespace Database\Factories;

use App\Models\Pengurus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengurusFactory extends Factory
{
    protected $model = Pengurus::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nama' => $this->faker->name(),
            'bagian' => $this->faker->randomElement([
                'tata_usaha', 'keuangan', 'keamanan', 'akademik', 'tahfidz', 'kesehatan', 'pengasuhan'
            ]),
            'no_hp' => $this->faker->phoneNumber(),
        ];
    }
}
