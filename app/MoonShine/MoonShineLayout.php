<?php

declare(strict_types=1);

namespace App\MoonShine;

use App\Models\ResCompany;
use App\MoonShine\Resources\ResCompanyResource;
use MoonShine\Components\Layout\{Content,
    Div,
    Flash,
    Footer,
    Header,
    LayoutBlock,
    LayoutBuilder,
    Menu,
    Profile,
    Search,
    TopBar};

use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\Dropdown;
use MoonShine\Components\Link;
use MoonShine\Components\When;
use MoonShine\Contracts\MoonShineLayoutContract;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Select;

final class MoonShineLayout implements MoonShineLayoutContract
{

    public static function build(): LayoutBuilder
    {


        $companies = ResCompany::all()->pluck('name', 'id')->toArray();
        return LayoutBuilder::make([
            TopBar::make([
                Menu::make()->top(),
                /*
                Dropdown::make(
                    toggler: 'Click me',
                )
                    ->items([
                        Link::make('#', 'Link 1'),
                        Link::make('#', 'Link 2'),
                        Link::make('#', 'Link 3'),
                    ])
                    ->searchable()
                    ->searchPlaceholder('Search item')
                */



            ])
                ->actions([
                    When::make(
                        static fn() => config('moonshine.auth.enable', true),
                        static fn() => [

                            //Este select se puede usar para cambiar de compania en el sistema de planillas los cuales estan en la tabla res_company, estonces el usuario debe selecionar una opcion y se guardar esa opcion en la session del usuario  y se debe mostrar en el topbar


                            Div::make([
                                Select::make('Empresas', 'company')
                                    ->options($companies)
                                    ->default(session('company', key($companies)))
                                    ->native()





                            ]),
                             Profile::make() ],

                    )
                ]),
            LayoutBlock::make([
                Flash::make(),

                Header::make([
                    Search::make(),

                ]),

                Content::make(),
                Footer::make()
                    ->copyright(fn(): string => sprintf(
                        <<<'HTML'
                            &copy; Todos los derechos reservados 2024 | Sistema de planillas de RRHH

                        HTML,
                        now()->year
                    ))
                    ->menu([
                        'https://moonshine-laravel.com/docs' => 'Documentation',
                    ]),
            ])->customAttributes(['class' => 'layout-page']),

            //Cambiar el tipo de body class a layout-page
        ])->bodyClass('theme-minimalistic');
    }
}
