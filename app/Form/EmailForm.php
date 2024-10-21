<?php
declare(strict_types=1);
namespace App\Form;

use MoonShine\Components\FormBuilder;
use MoonShine\Fields\Text;

final class EmailForm
{
    public function __invoke(): FormBuilder
    {
        return FormBuilder::make()
            ->customAttributes([
                'class' => 'authentication-form',
            ])


            ->action(route('password.request.send'), value("email")) // Route name
            ->fields([
                Text::make('Email', 'email')
                    ->required()
                    ->customAttributes([
                        'autofocus' => true,
                        'autocomplete' => 'email',
                        'value' => old('email')

                    ]),
            ])->submit('Enviar link de reseteo', [
                'class' => 'btn-primary btn-lg w-full',
            ]);
    }
}
