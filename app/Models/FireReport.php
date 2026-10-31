<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FireReport extends Model
{
    protected $fillable = [
        'user_id',
        'area_id',
        'title',
        'description',
        'incident_time',
        'latitude',
        'longitude',
        'severity',
        'evidence_path',
        'status',
        'reviewed_by',
        'review_notes',
        'needs_forwarding',
        'forwarded_to',
        'forwarded_at',
    ];

    protected function casts(): array
    {
        return [
            'incident_time' => 'datetime',
            'forwarded_at' => 'datetime',
            'needs_forwarding' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ReportStatusLog::class, 'fire_report_id')->latest();
    }
}