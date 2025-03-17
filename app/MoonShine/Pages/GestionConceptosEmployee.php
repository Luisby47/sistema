<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\CrnubeSpreadsheetConceptosEmployee;
use App\Models\CrnubeSpreedsheatConceptos;
use App\Models\HrDepartment;
use App\Models\HrEmployee;
use App\Models\ResCompany;
use App\MoonShine\Field\CustomPreview;
use App\MoonShine\Resources\CrnubeSpreedsheatConceptosResource;
use ForestLynx\MoonShine\Fields\Decimal;
use Illuminate\Support\Facades\Cache;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FormBuilder;
use MoonShine\Components\Layout\Flash;
use MoonShine\Components\TableBuilder;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Collapse;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Divider;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\Heading;
use MoonShine\Decorations\LineBreak;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Enums\JsEvent;
use MoonShine\Enums\ToastType;
use MoonShine\Exceptions\FieldException;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Field;
use MoonShine\Fields\Fields;
use MoonShine\Fields\Number;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Relationships\HasOne;
use MoonShine\Fields\Select;
use MoonShine\Fields\Slug;
use MoonShine\Fields\Template;
use MoonShine\Fields\Text;
use MoonShine\Http\Responses\MoonShineJsonResponse;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;
use MoonShine\Pages\Page;
use MoonShine\Components\MoonShineComponent;

use MoonShine\Support\AlpineJs;
use function Laravel\Prompts\alert;
use function Symfony\Component\Translation\t;

class GestionConceptosEmployee extends Page
{

    protected array $with = ['concepto'];
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
        return $this->title ?: 'GestionConceptosEmployee';
    }

    public function updateSalary(MoonShineRequest $request)
    {

        $employeeId = $request->input('id');
        $salaryValue = $request->input('salary_value');

        Flash::make( 'Salario Actualizado', 'success');

         // Basic validation
        if (empty($employeeId)) {
             MoonShineUI::toast('Debe seleccionar un empleado.', 'error');
             return back();
        }

        if (!is_numeric($salaryValue)) {
            MoonShineUI::toast('El valor del salario no puede ser nulo.', 'error');
            return back();
        }

        $salaryConcept = CrnubeSpreedsheatConceptos::where('name', 'Salario Base')->first() ?? null;
        if (!$salaryConcept) {
            MoonShineUI::toast('Concepto "Salario Base" no encontrado.','error');
        }


          /*
        $conceptoEmployee = null;
        try {
            $conceptoEmployee = CrnubeSpreadsheetConceptosEmployee::where('employee_id', $employeeId)
                ->where('concepto_id', $salaryConcept->id)
                ->first();
        } catch (\Exception $e) {
            MoonShineUI::toast('Error al buscar el concepto del empleado.','error');
        }



        if (!$conceptoEmployee) {
            CrnubeSpreadsheetConceptosEmployee::create([
                'employee_id' => $employeeId,
                'concepto_id' => $salaryConcept->id,
                'company_id' => Cache::get('company'),
                'value' => $salaryValue
            ]);
        } else {
            // Actualiza el registro existente si es necesario
            $conceptoEmployee->update([
                'value' => $salaryValue
            ]);
        }
        */



        try {
             CrnubeSpreadsheetConceptosEmployee::updateOrCreate(
                  [
                      'employee_id' => $employeeId,
                      'concepto_id' => $salaryConcept->id,
                  ],
                  [
                      'company_id' => Cache::get('company'),
                      'value' =>  $salaryValue
                  ]
             );

        } catch (\Exception $e) {
             MoonShineUI::toast('Error al actualizar el salario: ' . $e->getMessage(), 'error');
        }
        MoonShineUI::toast('Salario Actualizado', 'success');
        return MoonShineJsonResponse::make()->toast('Salario Actualizado', ToastType::SUCCESS)->events([AlpineJs::event(JsEvent::TABLE_UPDATED, 'ingresos-table')]);

    }


    public function addConcepto(MoonShineRequest $request)
    {

        $employeeId = Cache::get('selected_employee');
        $conceptId = $request->input('select_concepto_id');
        $amount = $request->input('amount');

        // Basic validation

        if (is_null($conceptId)) {
            MoonShineUI::toast('Debe seleccionar un concepto.', 'error');
            return back();
        }

        if (empty($employeeId)) {
            MoonShineUI::toast('Debe seleccionar un empleado.', 'error');
            return back();
        }

        if (!is_numeric($amount)) {
            MoonShineUI::toast('El valor del salario no puede ser nulo.', 'error');
            return back();
        }

        try {
            CrnubeSpreadsheetConceptosEmployee::updateOrCreate(
                [
                    'employee_id' => $employeeId,
                    'concepto_id' => $conceptId,
                ],
                [
                    'company_id' => Cache::get('company'),
                    'value' => $amount
                ]
            );

        } catch (\Exception $e) {
            MoonShineUI::toast('Error al actualizar el concepto: ' . $e->getMessage(), 'error');
        }

        //return MoonShineJsonResponse::make()->toast('Concepto agregado', ToastType::SUCCESS)->events([AlpineJs::event(JsEvent::TABLE_UPDATED, 'ingresos-table')]);
        return back();

    }



    /**
     * @return list<MoonShineComponent>
     * @throws FieldException
     * @throws \Throwable
     */
    public function components(): array
	{
        return [
            Grid::make([

                // Columna de Información del Colaborador
                Column::make([
                    Block::make('Información del Colaborador',[

                            FormBuilder::make()
                                ->name('my-form')
                                ->fields([
                                    Select::make('Buscar Colaborador', 'identification_id')
                                        ->options(HrEmployee::query()->pluck('name_related', 'id')->toArray())
                                        ->placeholder('Selecciona un colaborador')
                                        ->searchable()
                                        ->nullable()

                                        ->reactive(function(Fields $fields, ?string $value): Fields {

                                            if ($value) {
                                                Cache::put('selected_employee' , $value); // Store in cache
                                            }
                                            $nameField = $fields->findByColumn('name');
                                            $idField = $fields->findByColumn('id');
                                            $salaryField = $fields->findByColumn('salary');

                                            if ($nameField || $idField || $salaryField) {

                                                // Get the employee
                                                $employee = HrEmployee::find($value);

                                                $nameField->setLabel($employee && $employee->name_related ? $employee->name_related : 'No disponible');
                                                $idField->setLabel($employee && $employee->identification_id ? $employee->identification_id : 'No disponible');


                                                // Get the salary of the employee
                                                $salary = null;
                                                try {
                                                    $salary = CrnubeSpreadsheetConceptosEmployee::where(['employee_id' => $employee->id , 'concepto_id' => CrnubeSpreedsheatConceptos::where('name', 'Salario Base')->first()->id])
                                                        ->first();
                                                }catch (\Exception $e) {
                                                }
                                                $salaryField->setValue($salary && $salary->value ? $salary->value : 0);
                                            }
                                            return $fields;
                                        }),
                                    Divider::make(),
                                    Collapse::make(
                                        'Información del Colaborador',[
                                            Flex::make([
                                                Heading::make('Nombre:'),
                                                CustomPreview::make('' , 'name')->reactive(),
                                            ])->justifyAlign('start')->itemsAlign('baseline'),

                                            Flex::make([
                                                Heading::make('Identificacion:'),
                                                CustomPreview::make('' , 'id')->reactive(),
                                            ])->justifyAlign('start')->itemsAlign('baseline'),
                                        Number::make('Salario Base', 'salary')->reactive(lazy: true),
                                        ActionButton::make('Actualizar Salario Base')
                                            ->primary()
                                            ->method('updateSalary')
                                            ->withParams([
                                                'id' => '[data-column="identification_id"]',
                                                'salary_value' => '#salary'
                                            ])->dispatchEvent(AlpineJs::event(JsEvent::TABLE_UPDATED, 'ingresos-table')),

                                    ])->persist(fn () => false),

                                ])->hideSubmit(),
                        ])
                ])->columnSpan(5),


                // Columna de Ingresos y Deducciones
                Column::make('Ingresos y Deducciones',[
                    Block::make([

                        Tabs::make([

                            // Ingresos
                            Tab::make('Ingresos', array(
                                FormBuilder::make()
                                    ->name('ingresos-form')
                                    ->fields(array(
                                        Select::make('Buscar', 'select_concepto_id')
                                            ->options(CrnubeSpreedsheatConceptos::query()->where(array('company_id' => Cache::get('company'), 'type' => 'ING'))
                                            ->pluck('name', 'id')->toArray())
                                            ->nullable()
                                            ->placeholder('Seleccione un ingreso')
                                            ->searchable()
                                            ->reactive(function(Fields $fields, mixed $value): Fields {

                                                // Get the value_type
                                                $Field = $fields->findByColumn('value_type');
                                                if ($Field) {
                                                    $concept = CrnubeSpreedsheatConceptos::find($value);
                                                    $Field->setLabel($concept && $concept->value_type ? ($concept->value_type === 'MONT' ? 'Monto' : ($concept->value_type === 'PORC' ? 'Porcentaje' : 'No disponible')) : 'No disponible');                                        }
                                                return $fields;
                                            }),
                                        Divider::make(),
                                        Flex::make(array(
                                            Heading::make('Tipo de Valor:'),
                                            CustomPreview::make('', 'value_type')->reactive(),
                                        ))->withoutSpace()->justifyAlign('start')->itemsAlign('baseline'),
                                        Number::make('Cantidad', 'amount'),

                                    ))->submit('Agregar')->asyncMethod('addConcepto')->async(asyncEvents: ['table-updated-ingresos-table']),
                                        Divider::make(),
                                        // Tabla de Ingresos
                                        TableBuilder::make()
                                            ->async()
                                            ->name('ingresos-table')
                                            ->fields(array(
                                                Text::make('Nombre' ),
                                                Number::make('Cantidad'),
                                            ))->items( CrnubeSpreadsheetConceptosEmployee::with('concepto')
                                                ->where('employee_id', Cache::get('selected_employee'))
                                                ->whereHas('concepto', function ($query) {
                                                    $query->where('type', 'ING');
                                                })
                                                ->get()
                                                ->map(fn($item) => array(
                                                    'nombre' => $item->concepto->name,
                                                    'cantidad' => $item->value
                                                )))
                                                ->sticky()->simple()->buttons(array(
                                                ActionButton::make('Eliminar'),


                                            )),
                                        Divider::make(),
                            )),

                            // Deducciones
                            Tab::make('Deducciones', [
                                FormBuilder::make()
                                    ->name('my-form2')
                                    ->fields([
                                    Select::make('Buscar', 'identification_id')
                                        ->options(CrnubeSpreedsheatConceptos::query()->where(['company_id' => Cache::get('company'), 'type' => 'DED' ])
                                            ->pluck('name', 'id')->toArray())
                                        ->nullable()
                                        ->placeholder('Seleccione una deducción')
                                        ->searchable()
                                        ->reactive(function(Fields $fields, ?string $value): Fields {

                                            $Field = $fields->findByColumn('valor_type');
                                            if ($Field) {
                                                $concept = CrnubeSpreedsheatConceptos::find($value);
                                                $Field->setLabel($concept && $concept->value_type ? ($concept->value_type === 'MONT' ? 'Monto' : ($concept->value_type === 'PORC' ? 'Porcentaje' : 'No disponible')) : 'No disponible');                                            }

                                            return $fields;
                                        }),
                                    Divider::make(),

                                Flex::make([
                                    Heading::make('Tipo de Valor:'),
                                    CustomPreview::make('', 'valor_type')->reactive(),
                                ])->withoutSpace()->justifyAlign('start')->itemsAlign('baseline'),
                                Number::make('Cantidad', 'amount'),
                                ])->submit('Agregar')->async(),
                                Divider::make(),

                                // Tabla de Deducciones
                                TableBuilder::make()
                                    ->async()
                                    ->name('ingresos-table')
                                    ->fields(array(
                                        Text::make('Nombre' ),
                                        Number::make('Cantidad'),
                                    ))->items( CrnubeSpreadsheetConceptosEmployee::with('concepto')
                                        ->where('employee_id', Cache::get('selected_employee'))
                                        ->whereHas('concepto', function ($query) {
                                            $query->where('type', 'DED');
                                        })
                                        ->get()
                                        ->map(fn($item) => array(
                                            'nombre' => $item->concepto->name,
                                            'cantidad' => $item->value
                                        )))
                                    ->sticky()->simple()->buttons(array(
                                        ActionButton::make('Eliminar')->method('deleteConcept'),


                                    )),
                                Divider::make(),
                            ])
                        ])

                    ])
                ])->columnSpan(7),
            ])
        ];
	}
}
