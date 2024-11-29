<?php

declare(strict_types=1);

namespace App\MoonShine;

use App\Models\ResCompany;

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

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;


use MoonShine\ActionButtons\ActionButton;

use MoonShine\Components\FormBuilder;
use MoonShine\Components\When;
use MoonShine\Contracts\MoonShineLayoutContract;

use MoonShine\Decorations\Block;
use MoonShine\Decorations\Flex;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;



final class MoonShineLayout implements MoonShineLayoutContract
{


    public static function build(): LayoutBuilder
    {


        return LayoutBuilder::make([
            TopBar::make([
                Menu::make()->top(),


            ]   )
                ->actions([
                    When::make(
                        static fn() => config('moonshine.auth.enable', true),
                        static fn() => [




                            //Otra solucion es usar flex y select y action para eliminar los problemas alertas y con method en acction se pasa el parametro del id de empresa
                            //https://moonshine-laravel.com/docs/resource/components/components-decoration_layout


                            FormBuilder::make()
                                    ->action(route('changeCompany'))
                                    ->customAttributes(['class' => 'flex-col items-center  '])
                                    ->fields([
                                        Select::make('', 'company')
                                            ->options(ResCompany::query()->pluck('name', 'id')->toArray())
                                            ->default(Cache::get('company'))
                                            ->native()
                                            ->customAttributes(['style' => 'border: 2px solid white !important;', 'class' => 'w-32   h-px rounded-md text-xs']),

                                    ])
                                    ->errorsAbove(false)
                                    ->submit('Cambiar Empresa', ['class' => 'w-32 border: 3px h-px  bg-blue-500 text-black rounded-md text-xs flex items-center']),
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
            Div::make([view('vendor.moonshine.components.modal_loading')]), // Correcta inclusión del modal
        ])->bodyClass('theme-minimalistic');
    }


}
