<?php

namespace App\Observers;

use App\Models\FireReport;
use App\Models\ReportStatusLog;
use Illuminate\Support\Facades\Auth;

class FireReportObserver
{
    public function updated(FireReport $fireReport): void
    {
        if ($fireReport->wasChanged('status')) {
            ReportStatusLog::create([
                'fire_report_id' => $fireReport->id,
                'changed_by' => Auth::id(),
                'old_status' => $fireReport->getOriginal('status'),
                'new_status' => $fireReport->status,
            ]);
        }
    }
}