<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkOrderRequest extends FormRequest
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
        $isSupervisor = $this->user()->hasRole('supervisor');

        if ($isSupervisor) {
            return [
                'status' => ['required', 'in:draft,submitted,approved,in_progress,completed,closed,rejected'],
            ];
        }

        return [
            'title'          => ['sometimes', 'string', 'max:255'],
            'description'    => ['sometimes', 'string'],
            'type'           => ['sometimes', 'in:corrective,preventive,inspection'],
            'priority'       => ['sometimes', 'in:low,medium,high,critical'],
            'status'         => ['sometimes', 'in:draft,submitted,approved,in_progress,completed,closed,rejected'],
            'assigned_to'    => ['nullable', 'integer', 'exists:users,id'],
            'scheduled_date' => ['nullable', 'date'],
            'completed_date' => ['nullable', 'date'],
            'notes'          => ['nullable', 'string'],
        ];
    }
}
