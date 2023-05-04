<?php

namespace App\Providers;

use App\Http\Repository\EloquentWorkerRepository;
use App\Http\Repository\WorkerRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(WorkerRepositoryInterface::class, EloquentWorkerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
