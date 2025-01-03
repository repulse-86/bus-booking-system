<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\User;
use App\Repositories\BookedTicketRepository;
use App\Repositories\BusRepository;
use App\Services\BookedTicketService;
use App\Services\BusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookedTicketTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $bus;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'type' => 'customer',
        ]);

        $this->bus = Bus::factory()->create();
    }

    public function test_create_booked_ticket(): void
    {
        $this->actingAs($this->user);

        $data = [
            'travel_date' => '2025-01-01',
            'customer_id' => $this->user->id,
            'bus_id' => $this->bus->id,
            'seat' => 1,
            'payment_image' => 'payment_image.png',
            'status' => 'pending',
        ];

        $bookedTicketService = new BookedTicketService(
            new BookedTicketRepository(),
            new BusService(new BusRepository())
        );

        $bookedTicket = $bookedTicketService->createBookedTicket($data);

        $this->assertDatabaseHas('booked_tickets', [
            'customer_id' => $this->user->id,
            'bus_id' => $this->bus->id,
            'status' => 'pending',
        ]);
    }

    public function test_update_booking_status()
    {
        $bookedTicket = $this->user->bookedTickets()->create([
            'travel_date' => '2025-01-02',
            'bus_id' => $this->bus->id,
            'seat' => 1,
            'payment_image' => 'payment_image.png',
            'status' => 'pending',
        ]);

        // Update the status using the service
        $bookedTicketService = new BookedTicketService(new BookedTicketRepository(),
            new BusService(new BusRepository()));
        $bookedTicketService->updateStatus($bookedTicket, 'approved');

        // Assertions
        $this->assertEquals('approved', $bookedTicket->fresh()->status);
    }
}
