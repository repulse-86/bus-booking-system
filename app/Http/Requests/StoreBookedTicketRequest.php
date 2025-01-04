<?php

namespace App\Http\Requests;

use App\Models\BookedTicket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class StoreBookedTicketRequest extends FormRequest
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
            'seat' => 'required|integer',
            'travel_date' => 'required|date|after_or_equal:today',
            'payment_image' => 'required|string',
        ];
    }

    /**
     * Add custom validation after the rules are applied.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->isSeatAvailableForDate()) {
                $validator->errors()->add('seat', 'The selected seat is not available for Bus ' . $this->bus_id . ' on '. Carbon::parse($this->travel_date)->format('M d, Y'));
            }

            if (!$this->isBusInCorrectWeek()) {
                $validator->errors()->add('travel_date', 'The selected bus is not available for the specified week.');
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

    private function isSeatAvailableForDate(): bool
    {
        $bus = BookedTicket::where('seat', $this->seat)
            ->where('bus_id', $this->bus_id)
            ->where('travel_date', $this->travel_date)
            ->exists();
        return !$bus;
    }
}