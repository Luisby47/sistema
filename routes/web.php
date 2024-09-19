<?php

use App\Http\Controllers\Auth\CrnubeAuthController;
use App\Http\Controllers\Company\CompanyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});


//Route::get('/login', 'App\Http\Controllers\Auth\CrnubeAuthController@login')->name('login');

// Enable authentication routes if enabled in the configuration
if (config('moonshine.auth.enable', true)) {
    Route::controller(CrnubeAuthController::class)
        ->group(static function (): void {
            Route::get('/login', 'login')->name('login');
            Route::post('/authenticate', 'authenticate')->name('authenticate');
            Route::get('/logout', 'logout')->name('logout');
        });
}

Route::post('/change-company', [CompanyController::class, 'changeCompany'])->name('change-company');
