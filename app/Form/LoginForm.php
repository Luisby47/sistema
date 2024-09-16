<?php
declare(strict_types=1);
namespace App\Form;

use MoonShine\Components\FormBuilder;
use MoonShine\Fields\Password;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;

final class LoginForm
{
    public function __invoke(): FormBuilder
    {
        return FormBuilder::make()
            ->customAttributes([
                'class' => 'authentication-form',
            ])
            ->action(moonshineRouter()->to('authenticate')) // Route name
            ->fields([
                Text::make('Email', 'username')
                    ->required()
                    ->customAttributes([
                        'autofocus' => true,
                        'autocomplete' => 'username',
                    ]),

                Password::make('Contraseña', 'password')
                    ->required(),

                Switcher::make('¿Recordarme?', 'remember'),
            ])->submit('Login', [
                'class' => 'btn-primary btn-lg w-full',
            ]);
    }
}
