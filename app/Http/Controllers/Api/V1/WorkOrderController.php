<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreWorkOrderRequest;
use App\Http\Requests\Api\UpdateWorkOrderRequest;
use App\Http\Resources\WorkOrderResource;
use App\Http\Traits\ApiResponse;
use App\Models\WorkOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkOrderController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = WorkOrder::with(['asset', 'requestedBy', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('wo_number', 'like', '%' . $request->search . '%');
            });
        }

        $workOrders = $query->latest()->paginate($request->integer('per_page', 15));

        return $this->success(
            WorkOrderResource::collection($workOrders)->response()->getData(true),
            'Work orders retrieved successfully'
        );
    }

    public function store(StoreWorkOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['requested_by'] = $request->user()->id;
        $data['wo_number']    = $this->generateWoNumber();

        $workOrder = WorkOrder::create($data);
        $workOrder->load(['asset', 'requestedBy', 'assignedTo']);

        return $this->created(new WorkOrderResource($workOrder), 'Work order created successfully');
    }

    public function show(WorkOrder $workOrder): JsonResponse
    {
        $workOrder->load(['asset', 'requestedBy', 'assignedTo']);

        return $this->success(new WorkOrderResource($workOrder), 'Work order retrieved successfully');
    }

    public function update(UpdateWorkOrderRequest $request, WorkOrder $workOrder): JsonResponse
    {
        $workOrder->update($request->validated());
        $workOrder->load(['asset', 'requestedBy', 'assignedTo']);

        return $this->success(new WorkOrderResource($workOrder), 'Work order updated successfully');
    }

    public function destroy(WorkOrder $workOrder): JsonResponse
    {
        if (!in_array($workOrder->status, ['draft', 'rejected'])) {
            return $this->error('Only draft or rejected work orders can be deleted', 422);
        }

        $workOrder->delete();

        return $this->noContent();
    }

    private function generateWoNumber(): string
    {
        $year   = now()->format('Y');
        $latest = WorkOrder::whereYear('created_at', $year)->count() + 1;

        return 'WO-' . $year . '-' . str_pad($latest, 3, '0', STR_PAD_LEFT);
    }
}
