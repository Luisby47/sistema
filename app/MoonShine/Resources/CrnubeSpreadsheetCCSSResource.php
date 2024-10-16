<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreadsheetCCSS;

use MoonShine\Fields\Number;
use MoonShine\Fields\Text;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Handlers\ImportHandler;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<CrnubeSpreadsheetCCSS>
 */
class CrnubeSpreadsheetCCSSResource extends ModelResource
{
    protected string $model = CrnubeSpreadsheetCCSS::class;

    protected bool $editInModal = true;
    protected bool $detailInModal = true;
    protected string $sortDirection = 'ASC';

    protected string $title = 'Parametros de CCSS';

    public function getActiveActions(): array
    {
        return ['view', 'update'];
    }

    public function import(): ?ImportHandler
    {
        return null;
    }

    public function export(): ?ExportHandler
    {
        return null;
    }

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable()->hideOnAll(),
                Text::make('Nombre', 'name')->sortable()->readonly(),
                Number::make('Valor', 'value')->sortable()->required(),
            ]),
        ];
    }

    /**
     * @param CrnubeSpreadsheetCCSS $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
