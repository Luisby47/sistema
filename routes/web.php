<?php

use App\Http\Controllers\Auth\CrnubeAuthController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Calculos\ControllerCalculos;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\ControllerConceptosEmployee;
use Illuminate\Support\Facades\Route;




//Route::get('/login', 'App\Http\Controllers\Auth\CrnubeAuthController@login')->name('login');

// Enable authentication routes if enabled in the configuration

Route::get('/', function () {
    return redirect(moonshineRouter()->home());
});

Route::post('/change-company', [CompanyController::class, 'changeCompany'])->name('changeCompany');
Route::get('/calculos', [ControllerCalculos::class, 'generarCalculos'])->name('generarCalculos');
Route::get('/comprobante-salarial', [ControllerCalculos::class, 'generarComprobanteSalarial'])->name('generarComprobanteSalarial');
// route('concepto_id.deleteConcept'))
//Route::post('/generate', [\App\Http\Controllers\ControllerConceptosEmployee::class, 'generate'])->name('generate');
Route::POST('/generate', [ControllerConceptosEmployee::class, 'generate'])
    ->name('generate'); // 👈 Ruta POST

if (config('moonshine.auth.enable', true)) {
    Route::controller(ResetPasswordController::class)
        ->group(static function (): void {
            Route::get('/forgot-password', 'index')->name('password.request');
            Route::post('/forgot-password', 'send')->name('password.request.send');

            Route::get('/reset-password/{token}', 'recoverIndex')
                ->name('password.reset');

            Route::post('/reset-password', 'recoverUpdate')
                ->name('password.reset.update');
        });
}


Route::group(moonshine()->configureRoutes(), static function (): void {
    Route::middleware(config('moonshine.auth.middleware', []))->group(function (): void {
        if (config('moonshine.auth.enable', true)) {
            Route::post('/profile', [ProfileController::class, 'store'])
                ->middleware(config('moonshine.auth.middleware', []))
                ->name('profile.store');
        }
    });});
# Company Routes to change company



#Auth Passwords




/*
Route::middleware(config('moonshine.auth.middleware', []))->group(function (): void {
});
*/

