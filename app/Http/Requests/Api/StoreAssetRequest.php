<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['required', 'string', 'max:100', 'unique:assets,code'],
            'asset_type_id' => ['required', 'integer', 'exists:asset_types,id'],
            'location'      => ['required', 'string', 'max:255'],
            'status'        => ['required', 'in:active,maintenance,retired'],
            'install_date'  => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}
