<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\CrnubeSpreadsheetAccount;
use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreadsheetAccounts;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<CrnubeSpreadsheetAccount>
 */
class CrnubeSpreadsheetAccountsResource extends ModelResource
{
    protected string $model = CrnubeSpreadsheetAccount::class;

    protected string $title = 'CrnubeSpreadsheetAccounts';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
            ]),
        ];
    }

    /**
     * @param CrnubeSpreadsheetAccount $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
