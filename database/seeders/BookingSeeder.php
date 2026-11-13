<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
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

        $customer = User::where('email', 'customer@email.com')->first();

        if (! $customer) {
            return;
        }

        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $now = Carbon::now();

        foreach (range(1, 30) as $index) {
            $createdAt = $faker->dateTimeBetween($sixMonthsAgo, $now);
            $travelDate = Carbon::instance($createdAt)->addDays($faker->numberBetween(1, 30))->format('Y-m-d');

            Booking::create([
                'customer_id' => $customer->id,
                'bus_id' => $faker->numberBetween(1, 15),
                'seat' => $faker->numberBetween(1, 30),
                'travel_date' => $travelDate,
                'payment_image' => 'none',
                'total_price' => $faker->numberBetween(100, 1000),
                'status' => $faker->randomElement($statuses),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
