<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use App\Models\Santri;
use App\Models\Tagihan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PembayaranFactory extends Factory
{
    protected $model = Pembayaran::class;

    public function definition(): array
    {
        return [
            'tagihan_id' => Tagihan::factory(),
            'santri_id' => Santri::factory(),
            'jumlah_bayar' => 500000.00,
            'tanggal_bayar' => $this->faker->date(),
            'metode_pembayaran' => $this->faker->randomElement(['tunai', 'transfer', 'qris']),
            'admin_id' => User::factory(),
        ];
    }
}
