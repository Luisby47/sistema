<?php
declare(strict_types=1);
namespace App\Form;

use MoonShine\Components\FormBuilder;
use MoonShine\Fields\Password;
use MoonShine\Fields\PasswordRepeat;
use MoonShine\Fields\Text;

final class ResetPassword
{
    public function __invoke(): FormBuilder
    {
        return FormBuilder::make()
            ->customAttributes([
                'class' => 'authentication-form',
            ])
            ->action(route('password.reset.update')) // Route name
            ->fields([
                Password::make(trans('moonshine::ui.resource.password'), 'password')
                    ->required()->eye(),
                PasswordRepeat::make(trans('moonshine::ui.resource.repeat_password'), 'password_repeat')
                    ->required()->eye(),
            ])->submit('Resetear contraseña', [
                'class' => 'btn-primary btn-lg w-full',
            ]);
    }
}
