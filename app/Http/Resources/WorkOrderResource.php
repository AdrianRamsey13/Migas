<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'wo_number'      => $this->wo_number,
            'title'          => $this->title,
            'description'    => $this->description,
            'type'           => $this->type,
            'priority'       => $this->priority,
            'status'         => $this->status,
            'scheduled_date' => $this->scheduled_date?->toDateString(),
            'completed_date' => $this->completed_date?->toDateString(),
            'notes'          => $this->notes,
            'asset'          => new AssetResource($this->whenLoaded('asset')),
            'requested_by'   => [
                'id'   => $this->requestedBy?->id,
                'name' => $this->requestedBy?->name,
            ],
            'assigned_to'    => $this->assignedTo ? [
                'id'   => $this->assignedTo->id,
                'name' => $this->assignedTo->name,
            ] : null,
            'created_at'     => $this->created_at->toDateTimeString(),
        ];
    }
}
