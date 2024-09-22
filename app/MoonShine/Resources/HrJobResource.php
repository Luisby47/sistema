<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;


use App\Models\CrnubeSpreadsheetRole;
use App\Models\HrDepartment;
use App\Models\ResCompany;
use Illuminate\Database\Eloquent\Model;
use App\Models\HrJob;

use MoonShine\Fields\Date;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Textarea;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Handlers\ImportHandler;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<HrJob>
 */
class HrJobResource extends ModelResource
{
    protected string $model = HrJob::class;

    protected string $title = 'Puestos';

    public string $column = 'name';

    protected bool $detailInModal = true;


    public function getActiveActions(): array
    {
        return ['view'];
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
                ID::make()->sortable(),
                Text::make('Nombre', 'name'),
                Date::make('Fecha de creación', 'create_date')
                    ->format('d/m/Y')
                    ->default(now()->toDateTimeString())
                    ->sortable(),

                BelongsTo::make('Compañia', 'company',
                    static fn (ResCompany $model) => $model->name, new ResCompanyResource()
                )->badge('green'),

                BelongsTo::make('Departamento', 'department',
                    static fn (HrDepartment $model) => $model->name, new HrDepartmentResource()
                )->badge('green'),

                Text::make('Estado', 'state'),
                Textarea::make('Descripción', 'description')
                    ->default('Sin descripción')
                    ->readonly(),

            ]),
        ];
    }

    /**
     * @param HrJob $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
