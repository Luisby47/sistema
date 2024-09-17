<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\CrnubeSpreadsheetRole;
use App\Models\ResUser;
use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreadsheetUser;

use MoonShine\Decorations\Heading;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Date;
use MoonShine\Fields\PasswordRepeat;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasOne;
use MoonShine\Fields\Select;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Handlers\ImportHandler;
use MoonShine\Models\MoonshineUserRole;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;
use Illuminate\Validation\Rule;

use MoonShine\Fields\Email;
use MoonShine\Fields\Password;
use MoonShine\Fields\Text;
use MoonShine\Fields\Image;
use MoonShine\Resources\MoonShineUserRoleResource;


/**
 * @extends ModelResource<CrnubeSpreadsheetUser>
 */

class CrnubeSpreadsheetUserResource extends ModelResource
{
    protected string $model = CrnubeSpreadsheetUser::class;

    protected string $title = 'Usuarios';
    // un array with con la tabla de crnuve_spreadsheet_roles

    protected bool $createInModal = true;
    protected bool $editInModal = true;

    protected array $with = ['role']; // MUY IMPORTANTE PARA QUE FUNCIONE EL BELONGS TO Y ROLES EN EL MENU
    protected bool $withPolicy = true; // Esta variable se encarga de habilitar o deshabilitar la política de acceso a la tabla (Importante despues de create policy a un recurso)


    // Esta función se encarga de mostrar las acciones que se pueden realizar en la tabla (Frontend) de manera general no por rol
    public function getActiveActions(): array
    {
        return ['create', 'update', 'delete', 'export'];
    }

    // Estas funciones se encargan de desactivar las accciones export y import en este modelo en especifico
    public function import(): ?ImportHandler
    {
        return null;
    }

    public function export(): ?ExportHandler
    {
        return null;
    }

    /**
     * @return list<MoonShineComponent|Field>
     */
    // Esta función se encarga de mostrar los campos de la tabla en la interfaz de administración (Frontend)
    public function fields(): array
    {
        return [
            /*
            Select::make('Res User ID', 'id')
                ->options(
                    ResUser::whereNotIn('id', CrnubeSpreadsheetUser::pluck('id'))
                        ->pluck('login', 'id')
                )
                ->required()
                ->sortable()
                ->searchable(),

            */
            //BelongsTo::make('Role', 'role', 'name'), //Tengo que cambiar fijarme en moonshine_user_resource




            Block::make([
                Tabs::make([
                    Tab::make(__('moonshine::ui.resource.main_information'), [
                        Select::make('Login', 'id')
                            ->options(
                                ResUser::pluck('login', 'id')->toArray()
                            )
                            ->required()
                            ->sortable()
                            ->searchable(),



                        BelongsTo::make('Rol',
                            'role',
                            static fn (CrnubeSpreadsheetRole $model) => $model->name,
                            new CrnubeSpreadsheetRoleResource(),
                        )->badge('purple'),

                        Text::make(__('moonshine::ui.resource.name'), 'name')
                            ->required(),

                        Image::make('Foto', 'avatar')
                            ->showOnExport()
                            ->disk(config('moonshine.disk', 'avatar'))
                            ->dir('moonshine_users')
                            ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif']),

                        Date::make(__('moonshine::ui.resource.created_at'), 'created_at')
                            ->format("d.m.Y")
                            ->default(now()->toDateTimeString())
                            ->sortable()
                            ->hideOnForm(),

                        Email::make(__('moonshine::ui.resource.email'), 'email')
                            ->sortable()
                            ->required(),
                    ]),

                    Tab::make(__('moonshine::ui.resource.password'), [
                        Heading::make(__('moonshine::ui.resource.change_password')),

                        Password::make(__('moonshine::ui.resource.password'), 'password')
                            ->customAttributes(['autocomplete' => 'new-password'])
                            ->hideOnIndex()
                            ->hideOnDetail()
                            ->eye(),

                        PasswordRepeat::make(__('moonshine::ui.resource.repeat_password'), 'password_repeat')
                            ->customAttributes(['autocomplete' => 'confirm-password'])
                            ->hideOnIndex()
                            ->hideOnDetail()
                            ->eye(),
                    ]),
                ]),
            ]),
        ];
    }

    /**
     * @return array{id: string,name: string, role_id: string, email: array, password: string}
     */
    // Esta función se encarga de validar los campos de la tabla al momento de crear o actualizar un registro
    public function rules(Model $item): array
    {
        return [
            'id' => 'required|exists:res_users,id|unique:crnube_spreadsheet_users,id',
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
                'min:12',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[^\s]{12,}$/',
                'required_with:password_repeat',
                'same:password_repeat',
            ],

        ];
    }
}
