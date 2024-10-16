<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreadsheetJornada;

use MoonShine\Fields\Number;
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

    protected string $title = 'Jornadas laborales';

    protected bool $createInModal = true;
    protected bool $editInModal = true;
    protected bool $detailInModal = true;

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Nombre','nombre')
                ->required(),
                Number::make('Cantidad de días','cant_dias')
                ->required(),
            ]),
        ];
    }

    /**
     * @param CrnubeSpreadsheetJornada $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array { return []; }

    public function import(): ?ImportHandler
    {
        return null;
    }

    public function export(): ?ExportHandler
    {
        return null;
    }
}
