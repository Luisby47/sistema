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
                Text::make('Token', 'token')
                    ->required()
                    ->customAttributes([
                        'autofocus' => true,
                        'autocomplete' => 'token',
                    ])
                    ->hideOnForm(),
                Text::make('Email', 'username')
                    ->required()
                    ->customAttributes([
                        'autofocus' => true,
                        'autocomplete' => 'username',
                        'readonly' => true
                    ]),
                Password::make(trans('moonshine::ui.resource.password'), 'password')
                    ->required()->eye(),
                PasswordRepeat::make(trans('moonshine::ui.resource.repeat_password'), 'password_repeat')
                    ->required()->eye(),
            ])->submit('Resetear contraseña', [
                'class' => 'btn-primary btn-lg w-full',
            ]);
    }
}
