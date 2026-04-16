<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'code'         => $this->code,
            'location'     => $this->location,
            'status'       => $this->status,
            'install_date' => $this->install_date->toDateString(),
            'asset_type'   => new AssetTypeResource($this->whenLoaded('assetType')),
            'created_at'   => $this->created_at->toDateTimeString(),
        ];
    }
}
