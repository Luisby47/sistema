<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreedsheatConceptos;

use MoonShine\Fields\Number;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Select;

/**
 * @extends ModelResource<CrnubeSpreedsheatConceptos>
 */
class CrnubeSpreedsheatConceptosResource extends ModelResource
{
    protected string $model = CrnubeSpreedsheatConceptos::class;

    protected string $title = 'Conceptos';

    protected bool $createInModal = true;
    protected bool $editInModal = True;
    protected bool $detailInModal = True;

    protected bool $import = True;

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Motivo','motivo')
                ->required()
                ->showOnExport(),
                Select::make('Tipo del Concepto','tipo_concepto')
                ->options([
                    'ING' => 'Ingreso',
                    'DED' => 'Deduccion'
                ])
                ->required()
                ->showOnExport()
                ->sortable()
                ->searchable(),
                Select::make('Tipo de valor','tipo_valor')
                ->options([
                    'MONT' => 'Monto',
                    'PORC' => 'Porcentaje',
                ])
                ->required()
                ->showOnExport()
                ->sortable()
                ->searchable(),
                Number::make('Valor','valor')
                ->required()
                ->showOnExport()
                ->sortable()
                ->step(0.01),
                Textarea::make('Observaciones','observaciones')
                ->default("")
                ->showOnExport()
            ]),
        ];
    }

    /**
     * @param CrnubeSpreedsheatConceptos $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'motivo' => ['required', 'string'],
            'tipo_concepto' => ['required', 'in:ING,DED'],
            'tipo_valor' => ['required', 'in:MONT,PORC'],
            'valor' => ['required', 'numeric', function ($attribute, $value, $fail) use ($item) {
                $tipoValor = $item->tipo_valor ?? request()->input('tipo_valor');

                if($tipoValor === 'MONT' && $value != intval($value)) {
                    $fail('El valor debe ser un numero entero');
                }
                if ($tipoValor === 'PORC' && $value == intval($value)) {
                    $fail('Debe incluir los decimales del valor');
                }
            }],
            'observaciones' => [''],
        ];
    }

    public function search(): array
    {
        return [
            'motivo', 'tipo_concepto'
        ];
    }
}