<?php

use App\Http\Controllers\Auth\CrnubeAuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
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




#Auth Passwords
if (config('moonshine.auth.enable', true)) {
    Route::controller(ResetPasswordController::class)
        ->group(static function (): void {
            Route::get('/forgot-password', 'index')->name('password.request');
            Route::post('/forgot-password', 'send')->name('password.request.send');

            Route::get('/reset-password/{token}', [ResetPasswordController::class, 'recoverIndex'])
                ->name('password.reset');

            Route::post('/reset-password', [ResetPasswordController::class, 'recoverUpdate'])
                ->name('password.reset.update');


        });
}



/*
Route::middleware(config('moonshine.auth.middleware', []))->group(function (): void {
});
*/

