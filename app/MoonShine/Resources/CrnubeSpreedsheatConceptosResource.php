<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\HrEmployee;
use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreedsheatConceptos;


use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
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

    protected string $title = 'Conceptos Salariales';

    protected bool $createInModal = true;
    protected bool $editInModal = true;
    protected bool $detailInModal = true;


    protected array $with = ['employee'];

    protected bool $import = true;

    public function getActiveActions(): array
    {
        return ['create', 'view', 'update', 'delete', 'massDelete'];
    }


    /**
     * @throws ValidationException
     */
    public function beforeImportFilling(array $data): array
    {
        foreach ($data as $row) {
            if (empty($row['name'])) {
                throw ValidationException::withMessages(['name' => 'El campo nombre es obligatorio.']);
            }
            if (!in_array($row['type'], ['ING', 'DED'])) {
                throw ValidationException::withMessages(['type' => 'El tipo debe ser ING o DED.']);
            }
            if (!in_array($row['value_type'], ['MONT', 'PORC'])) {
                throw ValidationException::withMessages(['value_type' => 'El tipo de valor debe ser MONT o PORC.']);
            }
            if (!is_numeric($row['value'])) {
                throw ValidationException::withMessages(['value' => 'El valor debe ser numérico.']);
            }

        }
        return $data;
    }


    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Motivo','name')
                ->required()
                ->showOnExport()
                    ->placeholder('Ejemplo: Aguinaldo')
                ->useOnImport(),
                Select::make('Tipo del Concepto','type')
                ->options([
                    'ING' => 'Ingreso',
                    'DED' => 'Deduccion'
                ])
                ->required()
                ->showOnExport()
                ->sortable()
                ->searchable()
                ->useOnImport(),
                Select::make('Tipo de valor','value_type')
                ->options([
                    'MONT' => 'Monto',
                    'PORC' => 'Porcentaje',
                ])
                ->required()
                ->showOnExport()
                ->sortable()
                ->searchable()
                ->useOnImport(),
                Number::make('Valor','value')
                ->required()
                ->showOnExport()
                ->sortable()
                ->step(0.01)
                    ->placeholder('0.00 - 100.00')

                ->useOnImport(),

                Textarea::make('Observaciones','note')
                ->default("")
                ->showOnExport()
                ->useOnImport()
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
            'name' => ['required', 'string' ,Rule::unique('crnube_spreadsheet_conceptos', 'name')->ignore($item) ],
            'type' => ['required', 'in:ING,DED'],
            'value_type' => ['required', 'in:MONT,PORC'],
            'value' => ['required', 'numeric', function ($attribute, $value, $fail) use ($item) {
                $tipoValor = $item->tipo_valor ?? request()->input('tipo_valor');

                if($tipoValor === 'MONT' && $value != intval($value)) {
                    $fail('El valor debe ser un numero entero');
                }
                if ($tipoValor === 'PORC' && $value == intval($value)) {
                    $fail('Debe incluir los decimales del valor');
                }
            }],
            'note' => [''],
        ];
    }

    public function search(): array
    {
        return [

        ];
    }


    /*
    public function import(): ?ImportHandler
    {
        return ImportHandler::make('Importar ingresos y deducciones')
            ->deleteAfter();
    }
    */
}
