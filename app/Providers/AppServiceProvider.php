<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\PatientRepositoryInterface;
use App\Repositories\PatientRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PatientRepositoryInterface::class, PatientRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
