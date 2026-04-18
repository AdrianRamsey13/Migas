<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isSupervisor = $this->user()->hasRole('supervisor');

        if ($isSupervisor) {
            return [
                'status' => ['required', 'in:active,maintenance,retired'],
            ];
        }

        return [
            'name'          => ['sometimes', 'string', 'max:255'],
            'code'          => ['sometimes', 'string', 'max:100', 'unique:assets,code,' . $this->route('asset')],
            'asset_type_id' => ['sometimes', 'integer', 'exists:asset_types,id'],
            'location'      => ['sometimes', 'string', 'max:255'],
            'status'        => ['sometimes', 'in:active,maintenance,retired'],
            'install_date'  => ['sometimes', 'date', 'before_or_equal:today'],
        ];
    }
}
