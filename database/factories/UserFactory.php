<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'no_hp' => $this->faker->phoneNumber,
            'password' => bcrypt('password'), // bisa diganti pakai Hash::make jika pakai helper
            'otp' => str_pad(strval(rand(0, 999999)), 6, '0', STR_PAD_LEFT),
            'otp_expiry' => now()->addMinutes(10),
            'role' => $this->faker->randomElement(['admin', 'user']),
            'avatar' => $this->faker->imageUrl(300, 300, 'people', true, 'Avatar'),
            'created_at' => now(),
        ];
    }
}
