<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportStatusLog extends Model
{
    protected $fillable = [
        'fire_report_id',
        'changed_by',
        'old_status',
        'new_status',
    ];

    public function fireReport(): BelongsTo
    {
        return $this->belongsTo(FireReport::class, 'fire_report_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
