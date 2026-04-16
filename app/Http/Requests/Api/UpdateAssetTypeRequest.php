<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => ['sometimes', 'string', 'max:255', 'unique:asset_types,name,' . $this->route('asset_type')],
            'maintenance_strategy' => ['sometimes', 'in:preventive,inspection'],
        ];
    }
}
