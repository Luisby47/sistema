<?php

namespace App\Http\Controllers\Auth;

use App\Form\LoginForm;
use App\Http\Controllers\Company;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use MoonShine\Http\Controllers\MoonShineController;

class CrnubeAuthController extends MoonShineController
{

    /**
     * Show the login form.
     */


    /**
     * Handle the login request.
     */
    public function authenticate(Request $request) : RedirectResponse
    {

        if (filled(config('moonshine.auth.pipelines', []))) {
            $request = (new Pipeline(app()))->send($request)->through(array_filter(
                config('moonshine.auth.pipelines')
            ))->thenReturn();
        }

        if($request instanceof RedirectResponse) {
            return $request;
        }

        $request->authenticate();

        return redirect()->intended('dashboard');
    }

    public function logout(Request $request) : RedirectResponse
    {
        // Logout the user and invalidate the session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Redirect to the login page

        // Use the moonshineRouter to redirect to the login page is the best practice because Moonshine that way can handle the redirections
        return redirect(route('login')); // Redirect to login page(posible cambiar)
    }





}
