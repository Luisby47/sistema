<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use ForestLynx\MoonShine\Fields\Decimal;
use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreadsheetTax;

use MoonShine\Enums\PageType;
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
use MoonShine\Enums\PageType as EnumsPageType;

/**
 * @extends ModelResource<CrnubeSpreadsheetTax>
 */
class CrnubeSpreadsheetTaxResource extends ModelResource
{
    protected string $model = CrnubeSpreadsheetTax::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected bool $editInModal = true;
    protected bool $detailInModal = true;
    protected string $sortDirection = 'ASC';

    protected string $title = 'Impuestos de Hacienda';
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
                /*
                Number::make('Rango de Impuesto ', 'tax_range')->sortable()->expansion('₡'),
                Number::make('Rango en Colones', 'col_range')->sortable()->expansion('₡'),
                */
                Decimal::make('Rango de Impuesto', 'tax_range')
                    ->precision(2)
                    ->expansion('₡')->sortable()->required(),
                Decimal::make('Rango en Colones', 'col_range')
                    ->precision(2)
                    ->expansion('₡')->sortable()->required(),
                Number::make('Porcentaje %', 'percentage')
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
     * @param CrnubeSpreadsheetTax $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
