<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreadsheetJornada;

use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use MoonShine\Enums\PageType;
use MoonShine\Exceptions\FieldException;
use MoonShine\Fields\Number;
use MoonShine\Fields\Select;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Handlers\ImportHandler;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Fields\Text;
use MoonShine\Fields\Date;
use MoonShine\Fields;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<CrnubeSpreadsheetJornada>
 */
class CrnubeSpreadsheetJornadaResource extends ModelResource
{
    protected string $model = CrnubeSpreadsheetJornada::class;
    protected ?PageType $redirectAfterSave = PageType::INDEX;
    protected string $title = 'Jornadas laborales';

    protected bool $createInModal = true;
    protected bool $editInModal = true;
    protected bool $detailInModal = true;

    /**
     * @return list<MoonShineComponent|Field>
     * @throws FieldException
     */
    public function getActiveActions(): array
    {
        return ['view', 'update'];
    }

    public function query(): Builder
    {

        return parent::query()->where('company_id',Cache::get('company') );
    }
    /**
     * @throws FieldException
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable()->hideOnAll(),
                Select::make('Nombre de Jornada Laboral','name')
                    ->options([
                        'Semanal' => 'Semanal',
                        'Quincenal' => 'Quincenal',
                        'Mensual' => 'Mensual',
                    ]),
                Number::make('Cantidad de días','days')
                ->required()
                ->placeholder('0-31')
                ->max(31)
                ->min(0)
                ->step(1),
                Number::make('Empresa','company_id')->hideOnAll()->default(Cache::get('company') ),
            ]),
        ];
    }

    /**
     * @param CrnubeSpreadsheetJornada $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array { return [
        'name' => [
            'required',
            'string',
            Rule::unique('crnube_spreadsheet_jornadas')
                ->where(function ($query) use ($item) {
                    return $query->where('company_id', $item->company_id ?? request()->input('company_id'));
                })
                ->ignore($item->id)
        ],
        'days' => [
            'required',
            'integer',
            'min:0',
            function ($attribute, $value, $fail) {
                $name = request()->input('name');
                if ($name === 'Semanal' && $value > 7) {
                    $fail('Para jornadas semanales, los días no pueden ser más de 7.');
                } elseif ($name === 'Quincenal' && $value > 15) {
                    $fail('Para jornadas quincenales, los días no pueden ser más de 15.');
                } elseif ($name === 'Mensual' && $value > 31) {
                    $fail('Para jornadas mensuales, los días no pueden ser más de 31.');
                }
            }
        ],
    ]; }

    public function import(): ?ImportHandler
    {
        return null;
    }

    public function export(): ?ExportHandler
    {
        return null;
    }
}
