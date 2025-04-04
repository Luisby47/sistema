<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\CrnubeSpreadsheetConceptosEmployee;
use App\Models\CrnubeSpreedsheatConceptos;
use App\Models\HrEmployee;
use App\MoonShine\Field\CustomPreview;
use Illuminate\Support\Facades\Cache;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FormBuilder;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Collapse;
use MoonShine\Decorations\Divider;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\Heading;
use MoonShine\Enums\JsEvent;
use MoonShine\Fields\Fields;
use MoonShine\Fields\Number;
use MoonShine\Fields\Select;
use MoonShine\Pages\Page;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Support\AlpineJs;

class GestionEnvioComprobantes extends Page
{
    /**
     * @return array<string, string>
     */
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'GestionEnvioComprobantes';
    }

    public function beforeRender(): void
    {
        Cache::put('selected_employee', null);
    }


    /**
     * @return list<MoonShineComponent>
     * @throws \Throwable
     */
    public function components(): array
	{
		return [
            Block::make('Información del Colaborador', [

                FormBuilder::make()
                    ->name('my-form')
                    ->fields([
                        //Selecciona el tipo de planilla
                        Select::make('Tipo de Planilla', 'spreadsheet_id')
                            ->options([
                                'SEMA' => 'Planilla Semanal',
                                'QUIN' => 'Planilla Quincenal',
                                'MENS' => 'Planilla Mensual',
                            ])
                            ->placeholder('Selecciona un tipo de planilla')
                            ->searchable()
                            ->nullable(),

                        Select::make('Buscar Colaborador', 'identification_id')
                            ->options(HrEmployee::query()->whereHas('department', function ($query) {
                                $query->where('company_id', Cache::get('company'));
                            })->pluck('name_related', 'id')->toArray())
                            ->placeholder('Selecciona un colaborador')
                            ->searchable()
                            ->nullable()
                            ->badge('red')
                            ->reactive(function (Fields $fields, ?string $value): Fields {

                                if ($value) {
                                    Cache::put('selected_employee', $value); // Store in cache
                                }
                                $nameField = $fields->findByColumn('name');
                                $idField = $fields->findByColumn('id');
                                $salaryField = $fields->findByColumn('salary');

                                if ($nameField || $idField || $salaryField) {
                                    // Get the employee
                                    $employee = HrEmployee::find($value);

                                    $nameField->setLabel($employee && $employee->name_related ? $employee->name_related : 'No disponible');
                                    $idField->setLabel($employee && $employee->identification_id ? $employee->identification_id : 'No disponible');

                                }
                                return $fields;
                            }),
                        Divider::make(),

                            Heading::make('Información del Colaborador'),
                            Flex::make([
                                Heading::make('Nombre:'),
                                CustomPreview::make('', 'name')->reactive(),
                            ])->justifyAlign('start')->itemsAlign('baseline'),

                            Flex::make([
                                Heading::make('Identificacion:'),
                                CustomPreview::make('', 'id')->reactive(),
                            ])->justifyAlign('start')->itemsAlign('baseline'),
                            Divider::make(),
                            ActionButton::make('Ver Comprobante Salarial', route('generarComprobanteSalarial'))->icon('heroicons.outline.envelope')->info(),
                                ActionButton::make('Enviar Comprobante')
                                ->primary()
                                ->method('updateSalary')
                                ->withParams([
                                    'id' => '[data-column="identification_id"]',
                                    'salary_value' => '[data-column="salary"]'
                                ])->dispatchEvent(AlpineJs::event(JsEvent::TABLE_UPDATED, 'ingresos-table'))->icon('heroicons.outline.envelope'),



                    ])->hideSubmit(),
            ])
        ];
	}
}
