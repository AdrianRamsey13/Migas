<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAssetTypeRequest;
use App\Http\Requests\Api\UpdateAssetTypeRequest;
use App\Http\Resources\AssetTypeResource;
use App\Http\Traits\ApiResponse;
use App\Models\AssetType;
use Illuminate\Http\JsonResponse;

class AssetTypeController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $assetTypes = AssetType::withCount('assets')->get();

        return $this->success(AssetTypeResource::collection($assetTypes), 'Asset types retrieved successfully');
    }

    public function store(StoreAssetTypeRequest $request): JsonResponse
    {
        $assetType = AssetType::create($request->validated());

        return $this->created(new AssetTypeResource($assetType), 'Asset type created successfully');
    }

    public function show(AssetType $assetType): JsonResponse
    {
        $assetType->loadCount('assets');

        return $this->success(new AssetTypeResource($assetType), 'Asset type retrieved successfully');
    }

    public function update(UpdateAssetTypeRequest $request, AssetType $assetType): JsonResponse
    {
        $assetType->update($request->validated());

        return $this->success(new AssetTypeResource($assetType), 'Asset type updated successfully');
    }

    public function destroy(AssetType $assetType): JsonResponse
    {
        if ($assetType->assets()->exists()) {
            return $this->error('Cannot delete asset type that still has assets', 422);
        }

        $assetType->delete();

        return $this->noContent();
    }
}
