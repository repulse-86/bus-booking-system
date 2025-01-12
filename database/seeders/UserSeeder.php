<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        User::factory()->create([
            'type' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@email.com',
            'mobile_number' => \Faker\Factory::create()->numerify('09#########'),
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'type' => 'customer',
            'name' => 'Kate Ruaza',
            'email' => 'lambsauceraw.218@gmail.com',
            'mobile_number' => \Faker\Factory::create()->numerify('09#########'),
            'password' => bcrypt('password'),
        ]);

        foreach (range(1, 48) as $index) {
            User::factory()->create([
                'type' => 'customer',
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'mobile_number' => $faker->numerify('09#########'),
                'password' => bcrypt('password'),
                'created_at' => $faker->dateTimeBetween(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()),
            ]);
        }
    }
}
