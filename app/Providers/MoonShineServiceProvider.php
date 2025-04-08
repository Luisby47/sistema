<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\CrnubeSpreadsheetRole;
use App\Models\CrnubeSpreedsheatConceptos;
use App\MoonShine\Handlers\CustomImportHandler;
use App\MoonShine\Pages\ColaboradorIngresosDeduciones;
use App\MoonShine\Pages\GestionConceptosEmployee;
use App\MoonShine\Pages\GestionEnvioComprobantes;
use App\MoonShine\Resources\CrnubeSpreadsheetAccountsResource;
use App\MoonShine\Resources\CrnubeSpreadsheetCCSSResource;
use App\MoonShine\Resources\CrnubeSpreadsheetConceptosEmployeeResource;
use App\MoonShine\Resources\CrnubeSpreadsheetHeaderResource;
use App\MoonShine\Resources\CrnubeSpreadsheetJornadaResource;
use App\MoonShine\Resources\CrnubeSpreadsheetTaxResource;
use App\MoonShine\Resources\CrnubeSpreadsheetUserResource;
use App\MoonShine\Resources\CrnubeSpreedsheatConceptosResource;
use App\MoonShine\Resources\HrDepartmentResource;
use App\MoonShine\Resources\HrEmployeeResource;
use App\MoonShine\Resources\HrJobResource;
use App\MoonShine\Resources\ResCompanyResource;
use Illuminate\Validation\Rules\Password;
use MoonShine\Handlers\ImportHandler;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;

use MoonShine\Contracts\Resources\ResourceContract;
use MoonShine\Menu\MenuElement;
use App\MoonShine\Pages\Page;
use Closure;
use MoonShine\Resources\ModelResource;
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
            // Todos los Resources de la carpeta Moonshien/Resources
            new CrnubeSpreedsheatConceptosResource,
            new CrnubeSpreadsheetCCSSResource,
            new CrnubeSpreadsheetJornadaResource,
            new CrnubeSpreadsheetTaxResource,
            new CrnubeSpreadsheetUserResource,
            new HrDepartmentResource,
            new HrEmployeeResource,
            new HrJobResource,
            new ResCompanyResource,
            new CrnubeSpreadsheetConceptosEmployeeResource,
            new CrnubeSpreadsheetHeaderResource(),
            new CrnubeSpreadsheetAccountsResource()






        ];
    }

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [
            new GestionConceptosEmployee

        ];
    }

    /**
     * @return Closure|list<MenuElement>
     */
    protected function menu(): array
    {
        return [
            MenuGroup::make('Gestionar Acciones de Personal', [
                MenuItem::make('Gestionar Conceptos Salariales a un Colaborador',
                    GestionConceptosEmployee::make('Gestionar Conceptos Salariales a un Colaborador', 'gestion_ingresos_deduciones')
                ),
                MenuItem::make('Gestionar comprobantes de pago',
                    GestionEnvioComprobantes::make('Gestionar Comprobantes de Pago', 'gestion_comprobantes_pago')
                ),
            ]),
            MenuGroup::make('Mantenimientos', [
                MenuItem::make('Empleados',
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
                MenuItem::make('Jornadas laborales',
                    new CrnubeSpreadsheetJornadaResource()
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

            ]   )->canSee(static function () {
                    return auth('moonshine')->user()->role_id === 1 && 2;}),

            MenuGroup::make('Procesar Planilla', [
                MenuItem::make('Cerrar Planilla',
                    new CrnubeSpreadsheetHeaderResource()
                ),
            ]),


            MenuGroup::make('Gestionar Reportes', [

            ]),

            MenuGroup::make('Gestionar Usuarios y Roles', [
                    MenuItem::make(
                        'Usuarios',
                        new CrnubeSpreadsheetUserResource(),

                    // ... otros recursos
                ),
            ])->canSee(static function () {
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
        return [



        ];
    }
    public function boot(): void
    {

        session()->put('locale', 'es');
        app()->setLocale(session()->get('locale'));

        parent::boot();



        moonShineAssets()->add([
            asset('vendor/moonshine/assets/custom.css'),
            asset('vendor/moonshine/assets/custom.js'),
            asset('vendor/moonshine/assets/app_v2.js'),

        ]);



        moonshineColors()
            ->background('#09304d')
            ->content('#061b2a')
            ->tableRow('#143e5c')
            ->dividers('#061b2a')
            ->borders('#061b2a')

            ->secondary('#D9D9D9', dark: true)
            ->primary('#09304d', dark: true)
            ->primary('#061b2a')
            ->secondary('#061b2a');

    }



}
