<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_id'       => ['required', 'integer', 'exists:assets,id'],
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string'],
            'type'           => ['required', 'in:corrective,preventive,inspection'],
            'priority'       => ['required', 'in:low,medium,high,critical'],
            'assigned_to'    => ['nullable', 'integer', 'exists:users,id'],
            'scheduled_date' => ['nullable', 'date', 'after_or_equal:today'],
            'notes'          => ['nullable', 'string'],
        ];
    }
}
