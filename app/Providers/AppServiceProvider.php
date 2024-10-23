<?php

namespace App\Providers;

use App\Models\CrnubeSpreadsheetUser;
use App\Policies\CrnubeSpreadsheetUserPolicy;
use Illuminate\Support\ServiceProvider;

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

        //
    }

    protected $policies = [
        CrnubeSpreadsheetUser::class => CrnubeSpreadsheetUserPolicy::class,
    ];
}
