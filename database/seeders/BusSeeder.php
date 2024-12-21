<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buses = [
            ['bus_type' => 'Bus 01', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Alabang', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 600],
            ['bus_type' => 'Bus 02', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Batangas Grand Terminal', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 700],
            ['bus_type' => 'Bus 03', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Batangas Pier', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 560],
            ['bus_type' => 'Bus 04', 'departure_location' => 'Balibago Complex', 'destination_location' => 'BGC', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 550],
            ['bus_type' => 'Bus 05', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Buendia', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 500],
            ['bus_type' => 'Bus 06', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Calamba', 'time_available_start' => '06:00', 'time_available_end' => '18:00', 'price_per_ticket' => 550],
            ['bus_type' => 'Bus 07', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Cubao', 'time_available_start' => '06:00', 'time_available_end' => '18:00', 'price_per_ticket' => 600],
            ['bus_type' => 'Bus 08', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Dasmariñas', 'time_available_start' => '06:00', 'time_available_end' => '18:00', 'price_per_ticket' => 650],
            ['bus_type' => 'Bus 09', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Lemery', 'time_available_start' => '06:00', 'time_available_end' => '18:00', 'price_per_ticket' => 370],
            ['bus_type' => 'Bus 10', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Mamatid', 'time_available_start' => '06:00', 'time_available_end' => '18:00', 'price_per_ticket' => 300],
            ['bus_type' => 'Bus 11', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Nuvali', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 250],
            ['bus_type' => 'Bus 12', 'departure_location' => 'Balibago Complex', 'destination_location' => 'One Ayala Terminal', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 130],
            ['bus_type' => 'Bus 13', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Pacita', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 500],
            ['bus_type' => 'Bus 14', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Pagsanjan', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 700],
            ['bus_type' => 'Bus 15', 'departure_location' => 'Balibago Complex', 'destination_location' => 'San Pablo', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 600],
            ['bus_type' => 'Bus 16', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Tagaytay', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 400],
            ['bus_type' => 'Bus 17', 'departure_location' => 'Balibago Complex', 'destination_location' => 'Tanauan', 'time_available_start' => '05:00', 'time_available_end' => '17:00', 'price_per_ticket' => 550],
        ];

        foreach ($buses as $bus) {
            Bus::create([
                'bus_type' => $bus['bus_type'],
                'departure_location' => $bus['departure_location'],
                'destination_location' => $bus['destination_location'],
                'time_available_start' => $bus['time_available_start'],
                'time_available_end' => $bus['time_available_end'],
                'price_per_ticket' => $bus['price_per_ticket'],
                'available_seats' => rand(20, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
