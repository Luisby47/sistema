<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\CrnubeSpreadsheetRole;
use App\Models\CrnubeSpreedsheatConceptos;
use App\MoonShine\Resources\CrnubeSpreadsheetCCSSResource;
use App\MoonShine\Resources\CrnubeSpreadsheetTaxResource;
use App\MoonShine\Resources\CrnubeSpreadsheetUserResource;
use App\MoonShine\Resources\CrnubeSpreedsheatConceptosResource;
use App\MoonShine\Resources\HrDepartmentResource;
use App\MoonShine\Resources\HrEmployeeResource;
use App\MoonShine\Resources\HrJobResource;
use App\MoonShine\Resources\ResCompanyResource;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;

use MoonShine\Contracts\Resources\ResourceContract;
use MoonShine\Menu\MenuElement;
use App\MoonShine\Pages\Page;
use Closure;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    /**
     * @return list<ResourceContract>
     */
    protected function resources(): array
    {
        return [
        ];
    }

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [];
    }

    /**
     * @return Closure|list<MenuElement>
     */
    protected function menu(): array
    {
        return [
            MenuGroup::make('Gestionar Acciones de Personal', [
                MenuItem::make(
                    'Usuarios',
                    new MoonShineUserResource()
                ),
            ]),
            MenuGroup::make('Mantenimientos', [
                MenuItem::make('Colaboradores',
                    new HrEmployeeResource()
                ),
                MenuItem::make('Departamentos',
                    new HrDepartmentResource()
                ),
                MenuItem::make('Puestos',
                    new HrJobResource()
                ),
                MenuItem::make('Empresas',
                    new ResCompanyResource()
                ),
                MenuItem::make('Conceptos Salariales',
                new CrnubeSpreedsheatConceptosResource()
                ),
                MenuItem::make('Parametros de CCSS',
                new CrnubeSpreadsheetCCSSResource()
                ),
                MenuItem::make('Impuestos de Hacienda',
                new CrnubeSpreadsheetTaxResource()
                ),
            ],'heroicons.outline.user-group')->canSee(static function () {
                    return auth('moonshine')->user()->role_id === 1 && 2;}),

            MenuGroup::make('Procesar Planilla', [
                MenuItem::make(
                    'Usuarios',
                    new MoonShineUserResource()
                ),
            ]),


            MenuGroup::make('Gestionar Reportes', [
                MenuItem::make(
                    'Gestionar Reportes',
                    new MoonShineUserResource()
                ),
            ]),

            MenuGroup::make('Gestionar Usuarios y Roles', [
                    MenuItem::make(
                        'Usuarios',
                        new CrnubeSpreadsheetUserResource(),

                    // ... otros recursos
                ),
            ], 'heroicons.outline.user-group')->canSee(static function () {
                    return auth('moonshine')->user()->role_id === 1;}),
            /*
            MenuItem::make('Documentation', 'https://moonshine-laravel.com/docs')
                ->badge(fn() => 'Check')
                ->blank(),
            ,'heroicons.outline.user-group')->canSee(static function () {
                return auth('moonshine')->user()->role_id === 1;}
            */



        ];
    }

    /**
     * @return Closure|array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
    public function boot(): void
    {
        session()->put('locale', 'es');
        app()->setLocale(session()->get('locale'));

        parent::boot();

        moonShineAssets()->add([
            '/css/app.css',

        ]);

        moonshineColors()
            ->background('#09304d')
            ->content('#061b2a')
            ->tableRow('#061b2a')
            ->dividers('#D9D9D9')
            ->borders('#D9D9D9')
            ->buttons('#061b2a')
            ->primary('#061b2a')
            ->secondary('#D9D9D9');
    }

}
