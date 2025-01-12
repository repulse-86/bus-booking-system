<?php

namespace App\Repositories;

use App\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingRepository implements BookingRepositoryInterface
{
    private string $currentYear;

    public function __construct()
    {
        $this->currentYear = date('Y');
    }

    public function getBookingsByCustomer(?string $filterId, ?string $filterStatus, string $customerId): LengthAwarePaginator
    {
        return Booking::with('bus')
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

    public function getBookings(string $status, ?string $filterId, ?string $filterCustomerName): LengthAwarePaginator
    {
        return Booking::with('bus', 'customer')
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

    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function find(string $id): Booking
    {
        return Booking::with(['bus', 'customer'])->findOrFail($id);
    }

    public function delete(Booking $booking): void
    {
        $booking->delete();
    }

    public function updateBookingStatus(Booking $booking, string $status): void
    {
        $booking->status = $status;
        $booking->save();
    }

    public function getByStatusBookingCount(string $status): int
    {
        return Booking::where('status', $status)->count();
    }

    public function getCumulativePerMonthSales(): Collection
    {
        $cumulativeSalesPerMonth = DB::table('bookings')
            ->select(DB::raw('strftime("%m", bookings.created_at) as month_number'), DB::raw('sum(price_per_ticket) as total_sales'))
            ->join('buses', 'bookings.bus_id', '=', 'buses.id')
            ->whereRaw('strftime("%Y", bookings.created_at) = ?', [$this->currentYear])
            ->groupBy(DB::raw('strftime("%m", bookings.created_at)'))
            ->orderBy(DB::raw('strftime("%m", bookings.created_at)'))
            ->where('status', 'approved')
            ->get()
            ->map(function ($item) {
                $item->month_name = Carbon::createFromFormat('m', $item->month_number)->format('F');

                return $item;
            });

        $cumulativeSales = 0;

        $cumulativeSalesPerMonth = $cumulativeSalesPerMonth->map(function ($item) use (&$cumulativeSales) {
            $cumulativeSales += $item->total_sales;
            $item->total_sales = $cumulativeSales;

            return $item;
        });

        return $cumulativeSalesPerMonth;
    }

    public function getCumulativePerMonthBookingCount(): Collection
    {
        $cumulativeBookingsCountPerMonth = DB::table('bookings')
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
