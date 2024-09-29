<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreedsheatConceptos;

use MoonShine\ChangeLog\Components\ChangeLog;
use MoonShine\Enums\Layer;
use MoonShine\Fields\Number;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Handlers\ImportHandler;
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
    protected bool $editInModal = True;
    protected bool $detailInModal = True;

    protected bool $import = True;


    protected function onBoot(): void
    {
        $this->getPages()
            ->formPage()
            ->pushToLayer(
                Layer::BOTTOM,
                ChangeLog::make('Changelog', $this)
            );
    }
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
                ->showOnExport()
                ->useOnImport(),
                Select::make('Tipo del Concepto','tipo_concepto')
                ->options([
                    'ING' => 'Ingreso',
                    'DED' => 'Deduccion'
                ])
                ->required()
                ->showOnExport()
                ->sortable()
                ->searchable()
                ->useOnImport(),
                Select::make('Tipo de valor','tipo_valor')
                ->options([
                    'MONT' => 'Monto',
                    'PORC' => 'Porcentaje',
                ])
                ->required()
                ->showOnExport()
                ->sortable()
                ->searchable()
                ->useOnImport(),
                Number::make('Valor','valor')
                ->required()
                ->showOnExport()
                ->sortable()
                ->step(0.01)
                ->useOnImport(),
                Textarea::make('Observaciones','observaciones')
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

    public function import(): ?ImportHandler
    {
        return null;
    }

    public function export(): ?ExportHandler
    {
        return null;
    }

    /*
    public function import(): ?ImportHandler
    {
        return ImportHandler::make('Importar ingresos y deducciones')
            ->deleteAfter();
    }
    */
}
