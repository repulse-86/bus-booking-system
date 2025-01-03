<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'type' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@email.com',
            'mobile_number' => \Faker\Factory::create()->numerify('09#########'),
            'password' => bcrypt('password'),
        ]);

        $this->call([
            BusSeeder::class,
        ]);
    }
}
