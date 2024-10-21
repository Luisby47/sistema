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

use MoonShine\Components\When;
use MoonShine\Contracts\MoonShineLayoutContract;

use MoonShine\Fields\Select;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;

final class MoonShineLayout implements MoonShineLayoutContract
{

    /*
    public function changeCompany(MoonShineRequest $request): RedirectResponse
    {

        $request->session()->put('company', $request->input('company'));
        MoonShineUI::toast('Se cambio la empresa con existo', 'success');

        return back();
    }
    */

    public static function build(): LayoutBuilder
    {


        $companies = ResCompany::query()->pluck('name', 'id')->toArray();
        $company = Cache::get('company', array_key_first($companies));
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
                                Select::make( '','company')
                                    ->options($companies)
                                    ->default($company)
                                    ->native(),

                                 // Botón que aparece al intentar cambiar la empresa con confirmación
                                    ActionButton::make('Confirmar cambio')
                                        ->withConfirm(
                                            'Confirmar cambio de empresa',
                                            '¿Estás seguro de que deseas cambiar de empresa?',
                                            'Confirmar',
                                        )->method('changeCompany', params: [ 'company' => 'company' ]),
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
