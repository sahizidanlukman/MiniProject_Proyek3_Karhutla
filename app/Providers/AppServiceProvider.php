<?php

namespace App\Providers;

use App\Models\FireReport;
use App\Observers\FireReportObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        FireReport::observe(FireReportObserver::class);
    }
}