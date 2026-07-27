<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WaliSantri;
use Illuminate\Database\Eloquent\Factories\Factory;

class WaliSantriFactory extends Factory
{
    protected $model = WaliSantri::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nama' => $this->faker->name(),
            'no_hp' => $this->faker->phoneNumber(),
            'pekerjaan' => $this->faker->jobTitle(),
        ];
    }
}
