<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\ResCompany;
use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreadsheetHeader;

use Illuminate\Http\RedirectResponse;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Buttons\CreateButton;
use MoonShine\Components\Badge;
use MoonShine\Components\FormBuilder;
use MoonShine\Contracts\MoonShineRenderable;
use MoonShine\Enums\JsEvent;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Date;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Support\AlpineJs;

/**
 * @extends ModelResource<CrnubeSpreadsheetHeader>
 */
class CrnubeSpreadsheetHeaderResource extends ModelResource
{
    protected string $model = CrnubeSpreadsheetHeader::class;
    protected ?PageType $redirectAfterSave = PageType::INDEX;
    protected bool $createInModal = true;

    protected array $with = ['company'];

    protected string $title = 'Cerrar Planilla';

    public function getActiveActions(): array
    {
        return [];
    }

    public function generate( $value_type)
    {
//        dd($request->all());

       // $value_type = $request->query('value_type'); // Obtiene el valor de la URL


        MoonShineUI::toast('Es' . $value_type, 'error');

        return back();
    }

    /**
     * @throws \Throwable
     */
    public function actions(): array
    {
        return [
            ActionButton::make('Cerrar Nueva Planilla', '#')
                ->primary()
                ->icon('heroicons.outline.plus')
                ->inModal(

                    title: 'Tipo de plantilla',
                    content: fn (): MoonShineRenderable =>
                    FormBuilder::make( action: route('generate'), method: 'POST')
                        ->fields([
                            Select::make('Tipo de plantilla', 'value_type')
                                ->options([
                                    'SEMA' => 'Semanal',
                                    'QUIN' => 'Quincenal',
                                    'MENS' => 'Mensual',
                                ])
                                ->required(),
                            Textarea::make('Observaciones', 'notes')
                        ])


                        // withconfirm se puede meter el formulario datos
//                        ->buttons([
//                            // Aquí el botón actuará como submit del formulario.
//                            ActionButton::make('Generar'
//                            )->async(method: 'POST')
//                                ->primary()
//                                ->withConfirm(
//
//                                    'Generar',
//                                    '¿Estás seguro de generar la planilla?',
//                                    method: 'POST',
//                                )
//
//                        ])
                    ->submit('Generar'),
                    name: 'my-modal'
                ),
        ];
    }


//    public function actions(): array
//    {
//        return [
//            ActionButton::make('Cerrar Nueva Planilla')->primary()->icon('heroicons.outline.plus')->inModal(
//                title: 'Tipo de plantilla',
//                content: fn(): MoonShineRenderable => Select::make('Tipo de plantilla', 'value_type')
//                    ->options([
//                        'SEMA' => 'Semanal',
//                        'QUIN' => 'Quincenal',
//                        'MENS' => 'Mensual',
//
//
//                    ])
//                    ->required(),
//                buttons: [
//                    ActionButton::make('Generar', route('generarCalculos'))->primary()
//                        ->withConfirm(
//                            'Generar',
//                            '¿Estas seguro de generar la planilla?',
//                        ),
//                ],
//                name: 'my-modal',
//            ),
//        ];
//    }



    /**
     * @return list<MoonShineComponent|Field>
     */


//    public function fields(): array
//    {
//        return [
//            Block::make([
//                ID::make()->sortable(),
//            ]),
//        ];
//    }


    public function indexButtons(): array
    {
        return [
            ActionButton::make(
                'PDF',
                fn(Model $item) => '/endpoint?id=' . $item->getKey()
            )->error()->icon('heroicons.outline.document-arrow-down'),
            ActionButton::make(
                'Excel',
                fn(Model $item) => '/endpoint?id=' . $item->getKey()
            )->success()->icon('heroicons.outline.document-arrow-down'),

        ];
    }


    public function indexFields(): array
    {
        return [
            ID::make()->hideOnIndex(),
            Text::make('Número', 'number')
                ->sortable(),

            Select::make('Tipo', 'type')
                ->options([
                    'MENSUAL' => 'Mensual',
                    'QUINCENAL' => 'Quincenal',
                    'SEMANAL' => 'Semanal'
                ])
                ->sortable()
                ->badge(fn($status, $field) => $status === 'SEMANAL' ? 'pink' : 'green'),

            Date::make('Fecha inicio', 'date_start')
                ->format('d/m/Y')
                ->sortable(),

            Date::make('Fecha fin', 'date_end')
                ->format('d/m/Y')
                ->sortable(),

            Text::make('Estado', 'status')->badge(
                fn($status, $field) => match ($status) {
                    'Pendiente' => 'red',
                    'Cerrada' => 'green',
                    default => 'yellow'
                }
            ),

            BelongsTo::make('Empresa', 'company', static fn (ResCompany $model) => $model->name, new ResCompanyResource())->badge('green'),




            Date::make('Creado', 'created_at')
                ->format('d/m/Y H:i')
                ->sortable(),
            Text::make('Notas', 'notes')
                ->sortable(),

        ];
    }



    /**
     * @param CrnubeSpreadsheetHeader $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
