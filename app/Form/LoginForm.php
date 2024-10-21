<?php
declare(strict_types=1);
namespace App\Form;

use MoonShine\Components\FormBuilder;
use MoonShine\Components\Layout\Div;
use MoonShine\Components\Link;
use MoonShine\Fields\Password;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use Termwind\Components\Li;

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

                Div::make([
                    Switcher::make('¿Recordarme?', 'remember'),
                    Link::make(route('password.request'),'¿Olvidaste tu contraseña?')
                ])->customAttributes([
                    'class' => 'flex items-center', // Clases para alineación horizontal
                ]),


            ])->submit('Login', [
                'class' => 'btn-primary btn-lg w-full',
            ]);
    }
}
