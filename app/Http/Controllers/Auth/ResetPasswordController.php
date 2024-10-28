<?php
namespace App\Http\Controllers\Auth;
use App\Form\EmailForm;

use App\Form\ResetPassword;
use App\Models\CrnubeSpreadsheetUser;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use MoonShine\Components\Layout\Flash;
use MoonShine\Http\Controllers\MoonShineController;

class ResetPasswordController extends MoonShineController
{

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
            'password' => [
                'required',
                'min:8',
                'regex:/[a-z]/',      // debe contener al menos una letra minúscula
                'regex:/[A-Z]/',      // debe contener al menos una letra mayúscula
                'regex:/[0-9]/',      // debe contener al menos un número
                'regex:/[@$!%*?&]/',  // debe contener al menos un carácter especial
                ]
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
            ? redirect()->route('moonshine.index')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
