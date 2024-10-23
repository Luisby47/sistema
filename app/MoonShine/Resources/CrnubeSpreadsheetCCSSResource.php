<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreadsheetCCSS;

use MoonShine\Exceptions\FieldException;
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
     * @throws FieldException
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable()->hideOnAll(),
                Text::make('Aporte', 'name')->sortable()->readonly(),
                Number::make('Porcetanje %', 'value')
                    ->sortable()
                    ->required()
                    ->step(0.01)
                    ->placeholder('0.00 - 100.00')
                    ->min(0.00)
                    ->max(100.00)
                    ->expansion('%'),
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
