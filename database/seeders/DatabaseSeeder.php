<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KamarSeeder::class,
            FasilitasKamarSeeder::class,
            FurniturSeeder::class,
            BookingSeeder::class,
            TagihanSeeder::class,
            PembayaranSeeder::class,
            ReviewSeeder::class,
            KeluhanSeeder::class,
        ]);
    }
}
