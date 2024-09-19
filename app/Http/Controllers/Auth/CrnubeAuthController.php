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
    public function login() : View|RedirectResponse
    {
        // If the user is already authenticated, redirect to the Dashboard page
        if ($this->auth()->check()) {
            return redirect(
                //Home page is the dashboard
                moonshineRouter()->home()
            );
        }

        // Get the login form (made by me)
        $form = config('forms.login', LoginForm::class);

        // Return the login view (custom) with the form
        return view('auth.login', [
            'form' => new $form(),
        ]);
    }

    /**
     * Handle the login request.
     */
    public function authenticate(Request $request) : RedirectResponse
    {

        /*
        // Validate the request from the form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to login the user with the credentials
        if (Auth::guard('moonshine')->attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect to the dashboard page
            return redirect()->intended('dashboard');
        }
        */


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


    // Show the profile for the authenticated user. (lo dudo)
    public function showProfile() : View|RedirectResponse
    {
        $user = Auth::user();
        return view('auth.crnube-profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:crnube_spreadsheet_users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if ($data['password']) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();

        return redirect()->route('profile')->with('status', 'Profile updated successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
