<?php
namespace App\Http\Controllers\Auth;
use App\Form\EmailForm;

use App\Form\LoginForm;
use App\Form\ResetPassword;
use App\Models\CrnubeSpreadsheetUser;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

use MoonShine\Components\Layout\Flash;
use MoonShine\Http\Controllers\MoonShineController;

class ResetPasswordController extends MoonShineController
{

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
        return view('vendor.moonshine.auth.login', [
            'form' => new $form(),
        ]);
    }
    public function index() : View|RedirectResponse
    {

        $form = EmailForm::class;


        return view('vendor.moonshine.auth.forgot-password', [
            'form' => new $form(),
        ]);
    }

    public function send(Request $request) : RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function recoverIndex(Request $request, String $token) : View|RedirectResponse
    {
        $form =  ResetPassword::class;



        return view('vendor.moonshine.auth.reset-password',
            [ 'form' => new $form(), 'token' => $token, 'email' => $request->get('email', ''  )]  );

    }

    public function recoverUpdate(Request $request) : RedirectResponse
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            //'contraseña' => 'sometimes|nullable|min:6|required_with:password_repeat|same:password_repeat' ,

            'password' => ['required_with:password_repeat','same:password_repeat', \Illuminate\Validation\Rules\Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols(),
            ],

        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_repeat', 'token'),
            function (CrnubeSpreadsheetUser $user, string $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect(moonshineRouter()->to('login'))->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
