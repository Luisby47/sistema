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
use Illuminate\Support\Facades\DB;
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
use MoonShine\Fields\File;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\ID;
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
use MoonShine\Notifications\MoonShineNotification;
use MoonShine\Pages\Page;
use MoonShine\Components\MoonShineComponent;

use MoonShine\Support\AlpineJs;
use Ramsey\Uuid\Type\Integer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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

    public function deleteConcept(MoonShineRequest $request)
    {
        $conceptId = $request->get('concepto_id');

        try {
            DB::table('crnube_spreadsheet_conceptos_employees')->where('concept_id', $conceptId)->delete();
        } catch (\Exception $e) {
            return MoonShineJsonResponse::make()
                ->toast('Error al eliminar el concepto: ' . $e->getMessage(), ToastType::ERROR);
        }

        return MoonShineJsonResponse::make()
            ->toast('Concepto Eliminado', ToastType::SUCCESS)
            ->events([
                AlpineJs::event(JsEvent::TABLE_UPDATED, 'ingresos-table'),
                AlpineJs::event(JsEvent::TABLE_UPDATED, 'deducciones-table'),
                AlpineJs::event(JsEvent::FORM_RESET, 'ingresos-form'),
                AlpineJs::event(JsEvent::FORM_RESET, 'deducciones-form')
            ]);

    }

    public function beforeRender(): void
    {
        Cache::put('selected_employee', null);
    }


    public function updateSalary(MoonShineRequest $request)
    {

        $employeeId = $request->input('id');
        $salaryValue = $request->input('salary_value');

        // Basic validation
        if (empty($employeeId)) {
            Cache::put('selected_employee', null);
            return MoonShineJsonResponse::make()
                ->toast('Debe seleccionar un empleado.', ToastType::WARNING)
                ->events([
                    AlpineJs::event(JsEvent::TABLE_UPDATED, 'ingresos-table'),
                    AlpineJs::event(JsEvent::TABLE_UPDATED, 'deducciones-table'),
                    AlpineJs::event(JsEvent::FORM_RESET, 'ingresos-form'),
                    AlpineJs::event(JsEvent::FORM_RESET, 'deducciones-form')
                ]);
        }

        if (!is_numeric($salaryValue)) {
            return MoonShineJsonResponse::make()
                ->toast('Concepto "Salario Base" no encontrado', ToastType::ERROR);
        }

        $salaryConcept = CrnubeSpreedsheatConceptos::where('name', 'Salario Base')->first() ?? null;

        if (!$salaryConcept) {
            return MoonShineJsonResponse::make()
                ->toast('Concepto "Salario Base" no encontrado', ToastType::ERROR);
        }

        try {
            /*
            DB::table('crnube_spreadsheet_conceptos_employees')
                ->where('employee_id', $employeeId)
                ->where('concepto_id', $salaryConcept->id)
                ->where('company_id', Cache::get('company'))
                ->update(['value' => $salaryValue]);
            */
            DB::table('crnube_spreadsheet_conceptos_employees')->updateOrInsert(
                [
                    'employee_id' => $employeeId,
                    'concept_id' => $salaryConcept->id,
                ],
                ['value' => $salaryValue]
            );

        } catch (\Exception $e) {
            return MoonShineJsonResponse::make()
                ->toast('Error al actualizar el salario: ' . $e->getMessage(), ToastType::ERROR);
        }


        return MoonShineJsonResponse::make()
            ->toast('Salario Actualizado ', ToastType::SUCCESS)
            ->events([
                AlpineJs::event(JsEvent::TABLE_UPDATED, 'ingresos-table'),
                AlpineJs::event(JsEvent::TABLE_UPDATED, 'deducciones-table'),
                AlpineJs::event(JsEvent::FORM_RESET, 'ingresos-form'),
                AlpineJs::event(JsEvent::FORM_RESET, 'deducciones-form')
            ]);

    }


    public function addConceptoIngreso(MoonShineRequest $request)
    {

        $employeeId = Cache::get('selected_employee');
        $conceptId = $request->input('select_ingreso_concepto_id');
        $amount = $request->input('amountIngreso');


        // Basic validation
        if (empty($employeeId)) {
            return MoonShineJsonResponse::make()
                ->toast('Debe seleccionar un empleado.', ToastType::ERROR);
        }

        if (is_null($conceptId)) {
            return MoonShineJsonResponse::make()
                ->toast('Debe seleccionar un concepto.', ToastType::ERROR);
        }


        if (is_null($amount)) {
            return MoonShineJsonResponse::make()
                ->toast('El valor de la cantidad no puede ser nulo.', ToastType::ERROR);
        }
        if (!is_numeric($amount)) {
            return MoonShineJsonResponse::make()
                ->toast('El valor de la cantidad deber ser numerica', ToastType::ERROR);
        }




        $conceptValue = CrnubeSpreedsheatConceptos::query()->where('id', $conceptId)->first();
        try {
            DB::table('crnube_spreadsheet_conceptos_employees')->insert(
                [
                    'employee_id' => $employeeId,
                    'concept_id' => $conceptId,
                    'value' =>  $amount
                ]
            );
        } catch (\Exception $e) {
            return MoonShineJsonResponse::make()
                ->toast('Error al actualizar el salario: ' . $e->getMessage(), ToastType::ERROR);
        }

        return MoonShineJsonResponse::make()->toast('Concepto agregado: ' . $conceptValue->name, ToastType::SUCCESS)
            ->events(
                [AlpineJs::event(JsEvent::TABLE_UPDATED, 'ingresos-table'),
                    AlpineJs::event(JsEvent::FORM_RESET, 'ingresos-form')
                ]);


    }

    public function addConceptoDeduccion(MoonShineRequest $request)
    {

        $employeeId = Cache::get('selected_employee');
        $conceptId = $request->input('select_deduccion_concepto_id');
        $amount = $request->input('amountDeduccion');

        // Basic validation
        if (empty($employeeId)) {
            return MoonShineJsonResponse::make()
                ->toast('Debe seleccionar un empleado.', ToastType::ERROR);
        }

        if (is_null($conceptId)) {
            return MoonShineJsonResponse::make()
                ->toast('Debe seleccionar un concepto.', ToastType::ERROR);
        }


        if (is_null($amount)) {
            return MoonShineJsonResponse::make()
                ->toast('El valor de la cantidad no puede ser nulo.', ToastType::ERROR);
        }
        if (!is_numeric($amount)) {
            return MoonShineJsonResponse::make()
                ->toast('El valor de la cantidad deber ser numerica', ToastType::ERROR);
        }

        $conceptValue = CrnubeSpreedsheatConceptos::query()->where('id', $conceptId)->first();
        try {
            DB::table('crnube_spreadsheet_conceptos_employees')->insert(
                [
                    'employee_id' => $employeeId,
                    'concept_id' => $conceptId,

                    'value' =>  $amount
                ]
            );
        } catch (\Exception $e) {
            return MoonShineJsonResponse::make()
                ->toast('Error al actualizar el salario: ' . $e->getMessage(), ToastType::ERROR);
        }

        return MoonShineJsonResponse::make()->toast('Concepto agregado: ' . $conceptValue->name, ToastType::SUCCESS)
            ->events(
                [AlpineJs::event(JsEvent::TABLE_UPDATED, 'deducciones-table'),
                    AlpineJs::event(JsEvent::FORM_RESET, 'deducciones-form')
                ]);

    }



    public function download(): BinaryFileResponse
    {

        // Aqui se define la logica para pdf y excel

        $file = storage_path('app/public/plantillas/planilla.xlsx');

        if (!file_exists($file)) {
                MoonShineUI::toast('El archivo no existe.', 'error');
        }

        return response()->download($file);
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
                    Block::make('Información del Colaborador', [

                        FormBuilder::make()
                            ->name('my-form')
                            ->fields([
                                Select::make('Buscar Colaborador', 'identification_id')
                                    ->options(HrEmployee::query()->whereHas('department', function ($query) {
                                        $query->where('company_id', Cache::get('company'));
                                    })->pluck('name_related', 'id')->toArray())
                                    ->placeholder('Selecciona un colaborador')
                                    ->searchable()
                                    ->nullable()
                                    ->reactive(function (Fields $fields, ?string $value): Fields {

                                        if ($value) {
                                            Cache::put('selected_employee', $value); // Store in cache
                                            $fields->findByColumn('salary')->removeAttribute('disabled');
                                        } else {
                                            $fields->findByColumn('salary')->setAttribute('disabled', 'disabled');
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
                                            $salary = 0;
                                            try {
                                                $salaryConceptId = CrnubeSpreedsheatConceptos::where('name', 'Salario Base')->value('id');
                                                $salary = CrnubeSpreadsheetConceptosEmployee::where([
                                                    'employee_id' => $employee->id,
                                                    'concept_id' => $salaryConceptId
                                                ])->value('value');
                                            } catch (\Exception $e) {
                                                // Log the exception or handle it as needed
                                            }
                                            $salaryField->setValue($salary ?: 0);
                                        }
                                        return $fields;
                                    }),
                                Divider::make(),
                                Collapse::make(
                                    'Información del Colaborador', [
                                    Flex::make([
                                        Heading::make('Nombre:'),
                                        CustomPreview::make('', 'name')->reactive(),
                                    ])->justifyAlign('start')->itemsAlign('baseline'),

                                    Flex::make([
                                        Heading::make('Identificacion:'),
                                        CustomPreview::make('', 'id')->reactive(),
                                    ])->justifyAlign('start')->itemsAlign('baseline'),
                                    Number::make('Salario Base', 'salary')->step(1)->min(0)->disabled()->reactive(),
                                    ActionButton::make('Ver Comprobante Salarial', route('generarComprobanteSalarial')),
                                    ActionButton::make('Ver Cálculos', route('generarCalculos')),
                                    ActionButton::make('Actualizar')
                                        ->primary()
                                        ->method('updateSalary')
                                        ->withParams([
                                            'id' => '[data-column="identification_id"]',
                                            'salary_value' => '[data-column="salary"]'
                                        ])->dispatchEvent(AlpineJs::event(JsEvent::TABLE_UPDATED, 'ingresos-table')),

                                ])->persist(fn() => true),

                            ])->hideSubmit(),
                    ])
                ])->columnSpan(5),


                // Columna de Ingresos y Deducciones
                Column::make('Ingresos y Deducciones', [
                    Block::make([

                        Tabs::make([

                            // Ingresos
                            Tab::make('Ingresos', array(
                                FormBuilder::make()

                                    ->name('ingresos-form')
                                    ->fields(array(
                                        Select::make('Buscar', 'select_ingreso_concepto_id')
                                            ->options(function () {
                                                $employeeId = Cache::get('selected_employee');
                                                $companyId = Cache::get('company');

                                                // Obtener los IDs de los conceptos ya vinculados con el usuario
                                                $linkedConceptIds = CrnubeSpreadsheetConceptosEmployee::where('employee_id', $employeeId)
                                                    ->pluck('concept_id')
                                                    ->toArray();

                                                // Filtrar los conceptos disponibles excluyendo los IDs obtenidos
                                                return CrnubeSpreedsheatConceptos::where('company_id', $companyId)
                                                    ->where('type', 'ING')
                                                    ->whereNotIn('id', $linkedConceptIds)
                                                    ->pluck('name', 'id')
                                                    ->toArray();
                                            })
                                            ->nullable()
                                            ->placeholder('Seleccione un ingreso')
                                            ->searchable()
                                            ->disabled(Cache::get('selected_employee') == null)
                                            ->reactive(function (Fields $fields, mixed $value): Fields {

                                                // Get the value_type
                                                $Field = $fields->findByColumn('value_type');
                                                if ($Field) {
                                                    $concept = CrnubeSpreedsheatConceptos::find($value);
                                                    $Field->setLabel($concept && $concept->value_type ? ($concept->value_type === 'MONT' ? 'Monto' : ($concept->value_type === 'PORC' ? 'Porcentaje' : 'No disponible')) : 'No disponible');
                                                }
                                                return $fields;
                                            }),
                                        // PRUEBA DE DECARGAR




                                        Divider::make(),
                                        Flex::make(array(
                                            Heading::make('Tipo de Valor:'),
                                            CustomPreview::make('', 'value_type')->reactive(),
                                        ))->withoutSpace()->justifyAlign('start')->itemsAlign('baseline'),
                                        Number::make('Cantidad', 'amountIngreso')->disabled(Cache::get('selected_employee') != null),

                                    ))->submit('Agregar', attributes: ['data-no-spinner' => true])->asyncMethod('addConceptoIngreso')->async(asyncEvents: ['table-updated-ingresos-table', 'form-reset-ingresos-form']),
                                Divider::make(),
                                // Tabla de Ingresos
                                TableBuilder::make()
                                    ->async()
                                    ->name('ingresos-table')
                                    ->fields(array(
                                        Text::make('Nombre'),
                                        Text::make('Tipo de Valor'),
                                        Text::make('Valor'),
                                        Number::make('Cantidad'),
                                        Number::make('Total'),
                                    ))->items(CrnubeSpreadsheetConceptosEmployee::with('concepto')
                                        ->where('employee_id', Cache::get('selected_employee'))
                                        ->whereHas('concepto', function ($query) {
                                            $query->where('type', 'ING');
                                        })
                                        ->get()
                                        ->map(fn($item) => array(
                                            'concepto_id' => $item->concepto->id,
                                            'nombre' => $item->concepto->name,
                                            'tipo_de_valor' => $item->concepto->value_type,
                                            'valor' => $item->concepto->value,
                                            'cantidad' => $item->value,
                                            'total' => $item->concepto->value * $item->value
                                        )))
                                    ->sticky()->simple()->buttons(array(
                                        ActionButton::make('Eliminar')->method('deleteConcept', params: fn($item) => ['concepto_id' => $item['concepto_id'] ])->icon('heroicons.outline.x-circle')->error(),


                                    )),
                                Divider::make(),
                            )),

                            // Deducciones
                            Tab::make('Deducciones', [
                                FormBuilder::make()

                                    ->name('deducciones-form')
                                    ->fields([
                                        Select::make('Buscar', 'select_deduccion_concepto_id')
                                            ->options(function () {
                                                $employeeId = Cache::get('selected_employee');
                                                $companyId = Cache::get('company');

                                                // Obtener los IDs de los conceptos ya vinculados con el usuario
                                                $linkedConceptIds = CrnubeSpreadsheetConceptosEmployee::where('employee_id', $employeeId)
                                                    ->pluck('concept_id')
                                                    ->toArray();

                                                // Filtrar los conceptos disponibles excluyendo los IDs obtenidos
                                                return CrnubeSpreedsheatConceptos::where('company_id', $companyId)
                                                    ->where('type', 'DED')
                                                    ->whereNotIn('id', $linkedConceptIds)
                                                    ->pluck('name', 'id')
                                                    ->toArray();
                                            })
                                            ->nullable()
                                            ->placeholder('Seleccione una deducción')
                                            ->searchable()
                                            ->disabled(Cache::get('selected_employee') == null)
                                            ->reactive(function (Fields $fields, ?string $value): Fields {


                                                $Field = $fields->findByColumn('valor_type_deduccion');
                                                if ($Field) {
                                                    $concept = CrnubeSpreedsheatConceptos::find($value);
                                                    $Field->setLabel($concept && $concept->value_type ? ($concept->value_type === 'MONT' ? 'Monto' : ($concept->value_type === 'PORC' ? 'Porcentaje' : 'No disponible')) : 'No disponible');
                                                }

                                                return $fields;
                                            }),
                                        Divider::make(),

                                        Flex::make([
                                            Heading::make('Tipo de Valor:'),
                                            CustomPreview::make('', 'valor_type_deduccion')->reactive(),
                                        ])->withoutSpace()->justifyAlign('start')->itemsAlign('baseline'),
                                        Number::make('Cantidad', 'amountDeduccion')->disabled(Cache::get('selected_employee') != null),
                                    ])->submit('Agregar', attributes: ['data-no-spinner '=> true])->asyncMethod('addConceptoDeduccion')->async(asyncEvents: ['table-updated-deducciones-table', 'form-reset-deducciones-form']),
                                Divider::make(),

                                // Tabla de Deducciones
                                TableBuilder::make()
                                    ->async()
                                    ->name('deducciones-table')
                                    ->fields(array(

                                        Text::make('Nombre'),
                                        Text::make('Tipo de Valor'),
                                        Text::make('Valor'),
                                        Number::make('Cantidad'),
                                        Number::make('Total'),
                                    ))->items(CrnubeSpreadsheetConceptosEmployee::with('concepto')
                                        ->where('employee_id', Cache::get('selected_employee'))
                                        ->whereHas('concepto', function ($query) {
                                            $query->where('type', 'DED');
                                        })
                                        ->get()
                                        ->map(fn($item) => array(
                                            'concepto_id' => $item->concepto->id,
                                            'nombre' => $item->concepto->name,
                                            'tipo_de_valor' => $item->concepto->value_type,
                                            'valor' => $item->concepto->value,
                                            'cantidad' => $item->value,
                                            'total' => $item->concepto->value * $item->value
                                        )))
                                    ->sticky()->simple()->buttons(array(
                                        ActionButton::make('Eliminar')->method('deleteConcept', params: fn($item) => ['concepto_id' => $item['concepto_id'] ])->icon('heroicons.outline.x-circle')->error(),


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
