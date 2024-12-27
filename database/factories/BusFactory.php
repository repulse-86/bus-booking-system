<?php

namespace Database\Factories;

use App\Models\Bus;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusFactory extends Factory
{
    protected $model = Bus::class;

    public function definition(): array
    {
        return [
            'bus_type' => $this->faker->word(),
            'departure_location' => $this->faker->city(),
            'destination_location' => $this->faker->city(),
            'time_available_start' => $this->faker->time(),
            'time_available_end' => $this->faker->time(),
            'price_per_ticket' => $this->faker->numberBetween(100, 1000),
            'seats' => 30,
            'available_seats' => 30,
        ];
    }
}
