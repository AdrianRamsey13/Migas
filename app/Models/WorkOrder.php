<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrder extends Model
{
    protected $fillable = [
        'wo_number',
        'asset_id',
        'title',
        'description',
        'type',
        'priority',
        'status',
        'requested_by',
        'assigned_to',
        'scheduled_date',
        'completed_date',
        'notes',
        'started_at',
        'completed_at',
        'closed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'started_at'     => 'datetime',
        'completed_at'   => 'datetime',
        'closed_at'      => 'datetime',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }
}
