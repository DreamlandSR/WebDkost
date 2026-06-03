<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'nama'              => 'Admin Utama',
                'email'             => 'admin@example.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'no_telepon'        => '081234567890',
                'alamat'            => 'Jl. Admin No. 1, Jakarta',
                'role'              => 'admin',
                'created_at'        => now(),
            ],
            [
                'nama'              => 'Budi Santoso',
                'email'             => 'budi@example.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'no_telepon'        => '082345678901',
                'alamat'            => 'Jl. Mawar No. 10, Surabaya',
                'role'              => 'penyewa',
                'created_at'        => now(),
            ],
            [
                'nama'              => 'Siti Rahayu',
                'email'             => 'siti@example.com',
                'email_verified_at' => null,
                'password'          => Hash::make('password'),
                'no_telepon'        => null,
                'alamat'            => null,
                'role'              => 'penyewa',
                'created_at'        => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}