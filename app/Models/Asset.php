<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = [
        'name',
        'code',
        'asset_type_id',
        'location',
        'status',
        'install_date',
    ];

    protected $casts = [
        'install_date' => 'date',
    ];

    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }
}
