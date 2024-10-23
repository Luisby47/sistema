<?php

namespace App\MoonShine\Pages;

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
                            ->eye(),

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
}
