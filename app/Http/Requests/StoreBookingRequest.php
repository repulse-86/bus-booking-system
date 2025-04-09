<?php

namespace App\Http\Requests;

use App\Models\BookingSeat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|integer|exists:users,id',
            'bus_id' => 'required|integer|exists:buses,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'integer|min:1',
            'travel_date' => 'required|date|after_or_equal:today',
        ];
    }

    /**
     * Add custom validation after the rules are applied.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->hasAlreadyBookedToday()) {
                $validator->errors()->add(
                    'travel_date',
                    'You already have a booking for this date.'
                );
            }

            if ($this->areSeatsTaken()) {
                $validator->errors()->add(
                    'seats',
                    'One or more of the selected seats are already booked for Bus '.$this->bus_id.' on '.Carbon::parse($this->travel_date)->format('M d, Y')
                );
            }

            if (! $this->isBusInCorrectWeek()) {
                $validator->errors()->add(
                    'travel_date',
                    'The selected bus is not available for the specified week.'
                );
            }
        });
    }

    /**
     * Check if the bus is available for the correct week.
     */
    private function isBusInCorrectWeek(): bool
    {
        $travelDate = Carbon::parse($this->travel_date);
        $weekOfMonth = $travelDate->weekOfMonth;

        if ($weekOfMonth % 2 == 1) { // Odd weeks
            return $this->bus_id >= 1 && $this->bus_id <= 10;
        } else { // Even weeks
            return $this->bus_id >= 11 && $this->bus_id <= 17;
        }
    }

    /**
     * Check if any of the selected seats are already taken for the given bus_id and travel_date.
     */
    private function areSeatsTaken(): bool
    {
        $selectedSeats = is_array($this->seats) ? $this->seats : [];

        if (empty($selectedSeats)) {
            return false;
        }

        return BookingSeat::whereIn('seat', $selectedSeats)
            ->whereHas('booking', function ($query) {
                $query->where('bus_id', $this->bus_id)
                      ->where('travel_date', $this->travel_date);
            })
            ->exists();
    }

    private function hasAlreadyBookedToday(): bool
    {
        return auth()->user()
            ->bookings()
            ->whereDate('travel_date', $this->travel_date)
            ->exists();
    }
}
