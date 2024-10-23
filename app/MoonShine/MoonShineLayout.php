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
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;



final class MoonShineLayout implements MoonShineLayoutContract
{

    public function changeCompany(MoonShineRequest $request): RedirectResponse
    {

        Cache()->put('company', $request->input('company'));
        MoonShineUI::toast('Se cambio la empresa con existo', 'success');

        return back();
    }



    public static function build(): LayoutBuilder
    {



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



            ]   )
                ->actions([
                    When::make(
                        static fn() => config('moonshine.auth.enable', true),
                        static fn() => [

                            //Este select se puede usar para cambiar de compania en el sistema de planillas los cuales estan en la tabla res_company, estonces el usuario debe selecionar una opcion y se guardar esa opcion en la session del usuario  y se debe mostrar en el topbar

                                FormBuilder::make()
                                    ->action(route('changeCompany'))
                                    ->customAttributes(['class' => 'flex-col items-center  '])
                                    ->fields([
                                        Select::make('', 'company')
                                            ->options(ResCompany::query()->pluck('name', 'id')->toArray())
                                            ->default(Cache::get('company'))
                                            ->native()
                                            ->customAttributes(['style' => 'border: 3px solid white !important;', 'class' => 'w-32 h-px rounded-md text-xs'])

                                    ])
                                    ->submit('Cambiar Empresa', ['class' => 'h-px  bg-blue-500 text-black rounded-md text-xs flex items-center']),






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
