<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\CrnubeSpreadsheetRole;
use App\Models\ResCompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\HrDepartment;

use MoonShine\Fields\Date;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasOne;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Handlers\ImportHandler;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<HrDepartment>
 */
class HrDepartmentResource extends ModelResource
{
    protected string $model = HrDepartment::class;

    protected string $title = 'Departamentos';

    public string $column = 'name';

    protected array $with = ['company']; // Definir las relaciones que se van a cargar con el modelo

    protected bool $detailInModal = true;
    // protected string $sortColumn = ''; // Default sort field
    protected string $sortDirection = 'ASC';

    public function getActiveActions(): array
    {
        return ['view' ];
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


                // Relación con la compañia a la que pertenece el departamento
                BelongsTo::make('Compañia', 'company', static fn (ResCompany $model) => $model->name, new ResCompanyResource()),


                Date::make('Fecha de creación', 'create_date')
                    ->format('d/m/Y')
                    ->default(now()->toDateTimeString())
                    ->sortable(),
                Textarea::make('Nota', 'notes')
                    ->default('Sin notas')
                    ->readonly(),


            ]),
        ];
    }

    /**
     * @param HrDepartment $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }

    public function model(): string
    {
        return HrDepartment::class;
    }
}
