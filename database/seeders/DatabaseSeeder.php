<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'type' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@email.com',
            'mobile_number' => fake()->numerify('09#########'),
            'password' => bcrypt('password'),
        ]);

        $this->call([
            BusSeeder::class,
        ]);
    }
}
