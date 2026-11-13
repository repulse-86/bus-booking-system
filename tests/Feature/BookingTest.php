<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\User;
use App\Repositories\BookingRepository;
use App\Repositories\BusRepository;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $bus;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('broadcasting.default', 'log');

        $this->user = User::factory()->create([
            'type' => 'customer',
        ]);

        $this->bus = Bus::factory()->create();
    }

    public function test_create_booking(): void
    {
        $this->actingAs($this->user);

        $data = [
            'travel_date' => now(),
            'customer_id' => $this->user->id,
            'bus_id' => $this->bus->id,
            'seat' => 1,
            'payment_image' => 'payment_image.png',
            'status' => 'pending',
        ];

        $bookingService = new BookingService(
            new BookingRepository,
            new BusRepository
        );

        $booking = $bookingService->createBooking($data, 1);

        $this->assertDatabaseHas('bookings', [
            'customer_id' => $this->user->id,
            'bus_id' => $this->bus->id,
            'status' => 'pending',
        ]);
    }

    public function test_update_booking_status()
    {
$booking = $this->user->bookings()->create([
            'travel_date' => now(),
            'bus_id' => $this->bus->id,
            'seat' => 1,
            'payment_image' => 'payment_image.png',
            'total_price' => $this->bus->price_per_ticket,
            'status' => 'pending',
        ]);

        $bookingService = new BookingService(new BookingRepository,
            new BusRepository);
        $bookingService->updateBookingStatus($booking, 'approved', null);

        $this->assertEquals('approved', $booking->fresh()->status);
    }
}
