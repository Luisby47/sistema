<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\HrEmployee;
use App\Models\ResCompany;
use Exception;
use ForestLynx\MoonShine\Fields\Decimal;
use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreedsheatConceptos;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use MoonShine\Enums\PageType;
use MoonShine\Exceptions\FieldException;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;

use MoonShine\MoonShineUI;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Select;
use Illuminate\Contracts\Database\Eloquent\Builder;
/**
 * @extends ModelResource<CrnubeSpreedsheatConceptos>
 */
class CrnubeSpreedsheatConceptosResource extends ModelResource
{
    protected string $model = CrnubeSpreedsheatConceptos::class;

    protected string $title = 'Conceptos Salariales';
    protected ?PageType $redirectAfterSave = PageType::INDEX;
    protected bool $createInModal = true;
    protected bool $editInModal = true;
    protected bool $detailInModal = true;

    protected bool $errorsAbove = false;

    protected array $with = ['company'];

    protected bool $import = true;

    public function getActiveActions(): array
    {
        return ['create', 'view', 'update', 'delete', 'massDelete'];
    }


    public function query(): Builder
    {

        return parent::query()->where('company_id', Cache::get('company') );
    }


    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function beforeImportFilling(array $data): array
    {


        try {
            // Omitir la primera línea (títulos en español)
            $data = array_slice($data, 1);
            // Definir las reglas de validación
            $rules = [
                'name' => 'required|string|unique:crnube_spreadsheet_conceptos,name',
                'type' => 'required|in:ING,DED',
                'value_type' => 'required|in:MONT,PORC',
                'value' => 'required|numeric',
                'company_id' => 'required|exists:res_company,id',
                'note' => 'nullable|string',
            ];

            // Validar los datos
            $validator = Validator::make($data, $rules);

            // Si la validación falla, lanzar una excepción
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            return $data;
        } catch (ValidationException|Exception $e) {
            // Enviar un toast con el mensaje de error
            MoonShineUI::toast('Error al cargar los datos, corriga el excel', 'error');

            // Puedes lanzar una excepción o manejar el error de otra manera
            throw $e;
        }
    }

    public function afterImported(Model $item): Model
    {

        return $item;
    }


    /**
     * @return list<MoonShineComponent|Field>
     * @throws FieldException
     * @throws \JsonException
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Motivo de Concepto Salarial','name')
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




                /*
                BelongsTo::make('Empresa', 'company',  static fn (ResCompany $model) => $model->name, new ResCompanyResource())
                    ->default(Cache::get('company'))
                    ->hideOnAll()
                    ->showOnExport()
                    ->useOnImport(),
                */
                Text::make('Empresa','company_id')
                    ->default(Cache::get('company'))
                    ->readonly()
                    ->locked()
                    ->showOnExport()
                    ->useOnImport(),


                Textarea::make('Observaciones','note')
                ->default("")
                ->showOnExport()
                ->useOnImport(),




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
                if ($tipoValor === 'PORC') {
                    if ($value == intval($value)) {
                        $fail('Debe incluir los decimales del valor');
                    }
                    if ($value < 0.00 || $value > 100.00) {  // beforeSave
                        $fail('El valor debe estar entre 0.00 y 100.00');
                    }
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



}
