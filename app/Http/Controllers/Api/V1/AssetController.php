<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAssetRequest;
use App\Http\Requests\Api\UpdateAssetRequest;
use App\Http\Resources\AssetResource;
use App\Http\Traits\ApiResponse;
use App\Models\Asset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Asset::with('assetType');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('asset_type_id')) {
            $query->where('asset_type_id', $request->asset_type_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        $assets = $query->paginate($request->integer('per_page', 15));

        return $this->success(
            AssetResource::collection($assets)->response()->getData(true),
            'Assets retrieved successfully'
        );
    }

    public function store(StoreAssetRequest $request): JsonResponse
    {
        $asset = Asset::create($request->validated());
        $asset->load('assetType');

        return $this->created(new AssetResource($asset), 'Asset created successfully');
    }

    public function show(Asset $asset): JsonResponse
    {
        $asset->load('assetType');

        return $this->success(new AssetResource($asset), 'Asset retrieved successfully');
    }

    public function update(UpdateAssetRequest $request, Asset $asset): JsonResponse
    {
        $asset->update($request->validated());
        $asset->load('assetType');

        return $this->success(new AssetResource($asset), 'Asset updated successfully');
    }

    public function destroy(Asset $asset): JsonResponse
    {
        $asset->delete();

        return $this->noContent();
    }
}
