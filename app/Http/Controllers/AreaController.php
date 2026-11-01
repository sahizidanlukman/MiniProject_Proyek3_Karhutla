<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\JsonResponse;

class AreaController extends Controller
{

    public function summary(Area $area): JsonResponse
    {
        $activeReports = $area->fireReports()
            ->whereIn('status', ['menunggu_tinjauan', 'ditinjau', 'diteruskan'])
            ->latest()
            ->take(3)
            ->get(['id', 'title', 'severity', 'status', 'incident_time']);

        $activeCount = $area->fireReports()
            ->whereIn('status', ['menunggu_tinjauan', 'ditinjau', 'diteruskan'])
            ->count();

        return response()->json([
            'area_id' => $area->id,
            'name' => $area->name,
            'district' => $area->district,
            'risk_level' => strtoupper($area->risk_level),
            'contact_agency' => $area->contact_agency,
            'active_reports_count' => $activeCount,
            'recent_active_reports' => $activeReports,
        ]);
    }
}