<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departure_location = 'Balibago Complex';

        $buses = [
            ['bus_type' => 'Bus 01', 'departure_location' => $departure_location, 'destination_location' => 'Magalyanes', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 105],
            ['bus_type' => 'Bus 02', 'departure_location' => $departure_location, 'destination_location' => 'Ama', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 110],
            ['bus_type' => 'Bus 03', 'departure_location' => $departure_location, 'destination_location' => 'Pasay road', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 115],
            ['bus_type' => 'Bus 04', 'departure_location' => $departure_location, 'destination_location' => 'Dela rosa', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 120],
            ['bus_type' => 'Bus 05', 'departure_location' => $departure_location, 'destination_location' => 'Buendia', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 125],
            ['bus_type' => 'Bus 06', 'departure_location' => $departure_location, 'destination_location' => 'Ayala', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 70],
            ['bus_type' => 'Bus 07', 'departure_location' => $departure_location, 'destination_location' => 'Glorieta', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 75],
            ['bus_type' => 'Bus 08', 'departure_location' => $departure_location, 'destination_location' => 'Paseo', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 80],
            ['bus_type' => 'Bus 09', 'departure_location' => $departure_location, 'destination_location' => 'Makati med.', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 85],
            ['bus_type' => 'Bus 10', 'departure_location' => $departure_location, 'destination_location' => 'Chino Roses', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 90],
            ['bus_type' => 'Bus 11', 'departure_location' => $departure_location, 'destination_location' => 'Washington', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 95],
            ['bus_type' => 'Bus 12', 'departure_location' => $departure_location, 'destination_location' => 'Dela Rosa', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 100],
            ['bus_type' => 'Bus 13', 'departure_location' => $departure_location, 'destination_location' => 'Ayala one', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 115],
            ['bus_type' => 'Bus 14', 'departure_location' => $departure_location, 'destination_location' => 'LRT', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 120],
            ['bus_type' => 'Bus 15', 'departure_location' => $departure_location, 'destination_location' => 'Mantrade', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 125],
        ];

        foreach ($buses as $bus) {
            Bus::create([
                'bus_type' => $bus['bus_type'],
                'departure_location' => $bus['departure_location'],
                'destination_location' => $bus['destination_location'],
                'time_available_start' => $bus['time_available_start'],
                'time_available_end' => $bus['time_available_end'],
                'price_per_ticket' => $bus['price_per_ticket'],
                'seats' => 30,
                'available_seats' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
