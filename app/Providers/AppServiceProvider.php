<?php

namespace App\Providers;

use App\Models\FireReport;
use App\Observers\FireReportObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        FireReport::observe(FireReportObserver::class);
        Paginator::useBootstrapFive();
    }
}