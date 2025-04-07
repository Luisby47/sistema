<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\CrnubeSpreadsheetConceptosEmployee;
use App\Models\CrnubeSpreadsheetHeader;
use App\Models\CrnubeSpreedsheatConceptos;
use App\Models\HrEmployee;
use App\MoonShine\Field\CustomPreview;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FormBuilder;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Collapse;
use MoonShine\Decorations\Divider;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\Heading;
use MoonShine\Enums\JsEvent;
use MoonShine\Fields\Field;
use MoonShine\Fields\Fields;
use MoonShine\Fields\Number;
use MoonShine\Fields\Select;
use MoonShine\Fields\Template;
use MoonShine\Http\Responses\MoonShineJsonResponse;
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
        Cache::put('spreadsheet_type', null);
    }





    /**
     * @return list<MoonShineComponent>
     * @throws \Throwable
     */
    public function components(): array
    {
        return [
            Block::make('Información del Colaborador', [

                FormBuilder::make(action:  route('sendEmail') )
                    ->name('my-form')
                    ->async()
                    ->fields([
                        //Selecciona el tipo de planilla
                        Select::make('Tipo de Planilla', 'spreadsheet_id')
                            ->options([
                                'SEMANAL' => 'Planilla Semanal',
                                'QUINCENAL' => 'Planilla Quincenal',
                                'MENSUAL' => 'Planilla Mensual',
                            ])
                            ->placeholder('Selecciona un tipo de planilla')
                            ->searchable()
                            ->nullable()
                            ->required()

                            // Esta es la forma correcta de hacer el reactive segun la documentacion de moonshine
                            ->reactive(function(Fields $fields, ?string $value, Field $field, array $values): Fields {
                                //$field->setValue($value);
                                return tap($fields, callback: static fn($fields) => $fields
                                    ->findByColumn('spreadsheet_number')
                                    ?->options(
                                        CrnubeSpreadsheetHeader::query()
                                            ->where('company_id', Cache::get('company'))
                                            ->where('type', $value)
                                            ->pluck('number', 'id')->toArray()
                                    ));
                            }),

                        Select::make('Buscar Colaborador', 'identification_id')
                            ->options(HrEmployee::query()->whereHas('department', function ($query) {
                                $query->where('company_id', Cache::get('company'));
                            })->pluck('name_related', 'id')->toArray())
                            ->placeholder('Selecciona un colaborador')
                            ->searchable()
                            ->nullable()
                            ->required()
                            ->reactive(function(Fields $fields, ?string $value, Field $field, array $values): Fields {

                                if ($value) {
                                    Cache::put('selected_employee', $value); // Store in cache
                                }

                                $employee = HrEmployee::find($value);
//
                                return tap($fields, function($fields) use ($employee) { // 👈 Quita "static"

                                    $nameField = $fields->findByColumn('name');
                                    $idField = $fields->findByColumn('id');

                                    if ($nameField) {
                                        $nameField->setValue($employee?->name_related ?? 'No disponible');
                                    }

                                    if ($idField) {
                                        $idField->setValue($employee?->identification_id ?? 'No disponible');
                                    }
                                });
                            }),

                        // Un select para seleccionar la el numeoro de planilla del tipo de planilla selecionado
                        Select::make('Nomida', 'spreadsheet_number')
                            ->placeholder('Selecciona el numero de planilla')
                            ->searchable()
                            ->reactive()
                            ->required()
                            ->nullable(),

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





                    ])->buttons(
                        [ ActionButton::make('Ver Comprobante Salarial', route('generarComprobanteSalarial'))->icon('heroicons.outline.envelope')->info(),
                            ActionButton::make('Enviar Comprobante', route('sendEmail'))->icon('heroicons.outline.envelope')->primary(),
                            ]
                    )->hideSubmit(),
            ])
        ];
    }
}
