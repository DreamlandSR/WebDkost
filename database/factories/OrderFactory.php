<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(), // atau random id jika sudah ada user
            'waktu_order' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['pending', 'paid', 'shipped', 'completed', 'cancelled']),
            'total_harga' => $this->faker->randomFloat(2, 10000, 500000),
            'alamat_pemesanan' => $this->faker->address,
            'metode_pengiriman' => $this->faker->randomElement(['JNE', 'J&T', 'SiCepat', 'POS']),
            'notes' => $this->faker->optional()->sentence(),
            'created_at' => now(),
        ];
    }
}
