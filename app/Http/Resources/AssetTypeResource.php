<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'maintenance_strategy' => $this->maintenance_strategy,
            'assets_count'         => $this->whenCounted('assets'),
            'created_at'           => $this->created_at->toDateTimeString(),
        ];
    }
}
