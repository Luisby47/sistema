<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\CrnubeSpreadsheetRole;

use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<CrnubeSpreadsheetRole>
 */
class CrnubeSpreadsheetRoleResource extends ModelResource
{
    protected string $model = CrnubeSpreadsheetRole::class;

    protected string $title = 'CrnubeSpreadsheetRoles';

    public string $column = 'name';

    public function title(): string
    {
        return 'CrnubeSpreadsheetRoles';
    }
    /**
     * @return list<MoonShineComponent|Field>
     */


    // En duda porque no se ingresan roles por el momento
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Name', 'name')
                    ->required()
                    ->showOnExport(),
            ]),
        ];
    }

    /**
     * @param CrnubeSpreadsheetRole $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'name' => 'required|min:5',
        ];
    }

    public function search(): array
    {
        return [
            'id',
            'name',
        ];
    }
}
