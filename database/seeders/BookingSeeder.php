<?php

namespace Database\Seeders;

use App\Models\Booking;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $statuses = ['pending', 'approved', 'declined'];

        foreach (range(1, 50) as $index) {
            Booking::create([
                'customer_id' => 2,
                'bus_id' => $faker->numberBetween(1, 17),
                'seat' => $faker->numberBetween(1, 50),
                'travel_date' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                'payment_image' => 'none',
                'status' => $faker->randomElement($statuses),
                'created_at' => $faker->dateTimeBetween('now', '+1 year'),
            ]);
        }
    }
}
