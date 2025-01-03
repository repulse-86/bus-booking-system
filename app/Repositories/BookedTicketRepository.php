<?php

namespace App\Repositories;

use App\Interfaces\BookedTicketRepositoryInterface;
use App\Models\BookedTicket;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookedTicketRepository implements BookedTicketRepositoryInterface
{
    private string $currentYear;

    public function __construct()
    {
        $this->currentYear = date('Y');
    }

    public function getBookedTicketsByCustomer(?string $filterId, ?string $filterStatus, string $customerId): LengthAwarePaginator
    {
        return BookedTicket::with('bus')
            ->when($filterId, function ($query) use ($filterId) {
                $query->where('id', $filterId);
            })
            ->when($filterStatus, function ($query) use ($filterStatus) {
                $query->where('status', $filterStatus);
            })
            ->where('customer_id', $customerId)
            ->latest()
            ->paginate(10);
    }

    public function getBookedTickets(string $status, ?string $filterId, ?string $filterCustomerName): LengthAwarePaginator
    {
        return BookedTicket::with('bus', 'customer')
            ->when($filterId, function ($query) use ($filterId) {
                $query->where('id', '=', $filterId);
            })
            ->when($filterCustomerName, function ($query) use ($filterCustomerName) {
                $query->whereHas('customer', function ($query) use ($filterCustomerName) {
                    $query->where('name', 'like', '%'.$filterCustomerName.'%');
                });
            })
            ->where('status', $status)
            ->paginate(10);
    }

    public function create(array $data): BookedTicket
    {
        return BookedTicket::create($data);
    }

    public function find(string $id): BookedTicket
    {
        return BookedTicket::with(['bus', 'customer'])->findOrFail($id);
    }

    public function delete(BookedTicket $bookedTicket): void
    {
        $bookedTicket->delete();
    }

    public function updateStatus(BookedTicket $bookedTicket, string $status): void
    {
        $bookedTicket->status = $status;
        $bookedTicket->save();
    }

    public function getBookingsCountByStatus(string $status): int
    {
        return BookedTicket::where('status', $status)->count();
    }

    public function getCumulativeSalesPerMonth(): Collection
    {
        $cumulativeSalesPerMonth = DB::table('booked_tickets')
            ->select(DB::raw('strftime("%m", booked_tickets.created_at) as month'), DB::raw('sum(price_per_ticket) as total_sales'))
            ->join('buses', 'booked_tickets.bus_id', '=', 'buses.id')
            ->whereRaw('strftime("%Y", booked_tickets.created_at) = ?', [$this->currentYear])
            ->groupBy(DB::raw('strftime("%m", booked_tickets.created_at)'))
            ->orderBy(DB::raw('strftime("%m", booked_tickets.created_at)'))
            ->get();

        $cumulativeSales = 0;

        $cumulativeSalesPerMonth = $cumulativeSalesPerMonth->map(function ($item) use (&$cumulativeSales) {
            $cumulativeSales += $item->total_sales;
            $item->total_sales = $cumulativeSales;

            return $item;
        });

        return $cumulativeSalesPerMonth;
    }

    public function getCumulativeBookingsCountPerMonth(): Collection
    {
        $cumulativeBookingsCountPerMonth = DB::table('booked_tickets')
            ->select(
                DB::raw('strftime("%m", created_at) as month_number'),
                DB::raw('count(*) as total_bookings')
            )
            ->whereRaw('strftime("%Y", created_at) = ?', [$this->currentYear])
            ->where('status', 'approved')
            ->groupBy(DB::raw('strftime("%m", created_at)'))
            ->orderBy(DB::raw('strftime("%m", created_at)'))
            ->get()
            ->map(function ($item) {
                $item->month_name = Carbon::createFromFormat('m', $item->month_number)->format('F');
                return $item;
            });

        $cumulativeBookings = 0;
        $cumulativeBookingsCountPerMonth = $cumulativeBookingsCountPerMonth->map(function ($item) use (&$cumulativeBookings) {
            $cumulativeBookings += $item->total_bookings;
            $item->total_bookings = $cumulativeBookings;
            return $item;
        });

        return $cumulativeBookingsCountPerMonth;
    }
}
