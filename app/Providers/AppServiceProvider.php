<?php

namespace App\Providers;

use App\Http\Repository\EloquentWorkerRepository;
use App\Http\Repository\WorkerRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WorkerRepositoryInterface::class, function () {
            return new EloquentWorkerRepository(Log::getLogger());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
