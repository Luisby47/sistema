<?php

namespace App\MoonShine\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FlexibleRender;
use MoonShine\Components\FormBuilder;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Heading;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Password;
use MoonShine\Fields\PasswordRepeat;
use MoonShine\Fields\Text;
use MoonShine\Http\Controllers\ProfileController;
use MoonShine\MoonShineAuth;
use MoonShine\Pages\Page;
use MoonShine\TypeCasts\ModelCast;

class ProfilePage extends Page
{
    /**
     * @return array<string, string>
     */


    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title(),
        ];
    }

    public function title(): string
    {
        return __('moonshine::ui.profile');
    }

    public function fields(): array
    {
        return [
            Block::make([
                Tabs::make([
                    Tab::make(__('moonshine::ui.resource.main_information'), [
                        ID::make()
                            ->sortable()
                            ->showOnExport(),

                        Hidden::make(trans('moonshine::ui.login.username'), 'username')
                            ->setValue(auth()->user()
                                ->{config('moonshine.auth.fields.username', 'email')})
                            ->hideOnForm(),

                        Text::make(trans('moonshine::ui.resource.name'), 'name')
                            ->setValue(auth()->user()
                                ->{config('moonshine.auth.fields.name', 'name')})
                            ->required(),

                        Image::make('Foto de Perfil', 'avatar')
                            ->setValue(auth()->user()
                                ->{config('moonshine.auth.fields.avatar', 'avatar')} ?? null)
                            ->disk(config('moonshine.disk', 'public'))
                            ->options(config('moonshine.disk_options', []))
                            ->dir('moonshine_users')
                            ->removable()
                            ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif']),
                    ]),



                    Tab::make(trans('moonshine::ui.resource.password'), [
                        Heading::make(__('moonshine::ui.resource.change_password')),

                        Password::make(trans('moonshine::ui.resource.password'), 'password')
                            ->customAttributes(['autocomplete' => 'new-password'])
                            ->eye()   ->hint('No debe incluir espacios en blanco.
                                    Al menos un carácter especial (!, @, #, $, %, ^, &, *, etc).
                                    Debe tener una longitud mínima de 8 caracteres.
                                    Debe contener minúsculas.
                                    Debe contener mayúsculas.
                                    Debe contener números (0-9).'),

                        PasswordRepeat::make(trans('moonshine::ui.resource.repeat_password'), 'password_repeat')
                            ->customAttributes(['autocomplete' => 'confirm-password'])
                            ->eye(),
                    ]),
                ]),
            ]),
        ];
    }

    public function components(): array
    {
        return [
            FormBuilder::make(action([ProfileController::class, 'store']))
                ->customAttributes([
                    'enctype' => 'multipart/form-data',
                ])
                ->fields($this->fields())
                ->cast(ModelCast::make(MoonShineAuth::model()::class))
                ->submit(__('moonshine::ui.save'), [
                    'class' => 'btn-lg btn-primary',
                ]),

            FlexibleRender::make(
                view('moonshine::ui.social-auth', [
                    'title' => trans('moonshine::ui.resource.link_socialite'),
                    'attached' => true,
                ])
            ),
        ];
    }
    /**
     * @return array{id: string,name: string, role_id: string, email: array, password: string}
     */


    // Esta función se encarga de validar los campos de la tabla al momento de crear o actualizar un registro
    public function rules(Model $item): array
    {
        return [
            'id' => [
                'required',
                'exists:res_users,id',
                Rule::unique('crnube_spreadsheet_users', 'id')->ignore($item->id),
            ],
            'role_id' => 'required|exists:crnube_spreadsheet_roles,id',
            // Si el email ya existe, no es necesario que se ingrese uno nuevo (excepto si se está actualizando el registro) y debe ser un email válido y único y no puede ser nulo
            'email' => [
                'sometimes',
                'bail',
                'required',
                'email',
                Rule::unique('crnube_spreadsheet_users', 'email')->ignore($item),
            ],

            // Si la contraseña ya existe, no es necesario que se ingrese una nueva y debe tener al menos 6 caracteres y ser igual a la contraseña repetida
            /*
            'password' => $item->exists
                ? 'sometimes|nullable|min:6|required_with:password_repeat|same:password_repeat'
                : 'required|min:6|required_with:password_repeat|same:password_repeat',
            'name' => 'required',
            */
            'password' => [
                $item->exists ? 'sometimes' : 'required',
                'nullable',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[^\s]{12,}$/',
                'required_with:password_repeat',
                'same:password_repeat',
            ],

        ];
    }
}
