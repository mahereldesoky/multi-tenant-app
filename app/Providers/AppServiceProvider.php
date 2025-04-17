<?php

namespace App\Providers;

use App\Events\JobCreated;
use App\Listeners\SyncJobToElasticAfterResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            JobCreated::class,
            SyncJobToElasticAfterResponse::class,
        );
    }
}
