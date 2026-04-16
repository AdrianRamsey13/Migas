<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', 'max:255', 'unique:asset_types,name'],
            'maintenance_strategy' => ['required', 'in:preventive,inspection'],
        ];
    }
}
