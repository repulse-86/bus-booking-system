<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BusSeeder::class,
            BookingSeeder::class,
        ]);

        $filePath = "payments";

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->deleteDirectory($filePath);
        }
    }
}
