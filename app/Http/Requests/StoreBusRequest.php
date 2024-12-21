<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusRequest extends FormRequest
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
            'bus_type' => 'required|string|in:AC,Non-AC,Sleeper',
            'departure_location' => 'required|string|max:255',
            'destination_location' => 'required|string|max:255',
            'time_available_start' => 'required|date_format:H:i',
            'time_available_end' => 'required|date_format:H:i',
            'price_per_ticket' => 'required|numeric|min:100',
            'available_seats' => 'required|integer|min:1|max:50',
        ];
    }
}
