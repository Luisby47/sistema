<?php

declare(strict_types=1);

namespace App\MoonShine;

use App\Models\ResCompany;
use App\MoonShine\Resources\ResCompanyResource;
use MoonShine\Components\Layout\{Content,
    Flash,
    Footer,
    Header,
    LayoutBlock,
    LayoutBuilder,
    Menu,
    Profile,
    Search,
    TopBar};

use MoonShine\Components\Dropdown;
use MoonShine\Components\When;
use MoonShine\Contracts\MoonShineLayoutContract;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Select;

final class MoonShineLayout implements MoonShineLayoutContract
{

    public static function build(): LayoutBuilder
    {


        return LayoutBuilder::make([
            TopBar::make([
                Menu::make()->top(),



            ])
                ->actions([
                    When::make(
                        static fn() => config('moonshine.auth.enable', true),
                        static fn() => [Profile::make()],
                        static fn() =>[ ]

                    )
                ]),
            LayoutBlock::make([
                Flash::make(),
                Select::make('Empresas', 'company')
                    ->options([
                        'company' => 'Company',
                        'company' =>  'Company',
                    ])
                    ->default('Company')
                    ->customAttributes(['style' => 'background: red !important ;']),



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
