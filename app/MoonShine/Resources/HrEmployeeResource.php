<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;


use App\Models\HrDepartment;
use App\Models\HrJob;
use App\Models\ResCompany;
use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;

use Illuminate\Support\Facades\Cache;
use MoonShine\Fields\Date;
use MoonShine\Fields\Email;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Handlers\ImportHandler;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;
use Illuminate\Contracts\Database\Eloquent\Builder;



/**
 * @extends ModelResource<HrEmployee>
 */
class HrEmployeeResource extends ModelResource
{
    protected string $model = HrEmployee::class;

    protected string $title = 'Empleados';

    public string $column = 'name';

    protected array $with = ['department','job'];

    protected bool $detailInModal = true;
    protected string $sortDirection = 'ASC';


    // Quiero hacer  query(): Builder donde se muestren los datos si el usuario es de la compañia almacena en cache como comapny


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
    public function query(): Builder
    {
        return parent::query()
            ->join('hr_department', 'hr_employee.department_id', '=', 'hr_department.id')
            ->select('hr_employee.*')
            ->where('hr_department.company_id', Cache::get('company'));
    }

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable()->hideOnAll(),
                Text::make('Nombre del Empleado', 'name_related'),
                Text::make('Identificacion', 'identification_id')->mask('9-9999-9999'),
                // Belong de address
                Date::make('Fecha de creación', 'create_date')
                    ->format('d/m/Y')
                    ->default(now()->toDateTimeString())
                    ->sortable(),
                Text::make('Estado civil', 'marital'),

                //Relacion con la compañia
                BelongsTo::make('Departamento', 'department',
                    static fn (HrDepartment $model) => $model->name, new HrDepartmentResource())->badge('green'),
                //Relacion con el puesto
                BelongsTo::make('Puesto de Trabajo', 'job',
                    static fn (HrJob $model) => $model->name, new HrJobResource())->badge('green'),

                Number::make('Telefono de trabajo', 'work_phone'),
                Number::make('Telefono movil', 'mobile_phone'),
                Date::make('Fecha de cumpleaños', 'birthday')
                    ->format('d/m/Y')
                    ->default(now()->toDateTimeString())
                    ->sortable(),
                Email::make('Email', 'work_email'),
                Text::make('Lugar de trabajo', 'work_location'),
                Text::make('Genero', 'gender'),
                Text::make('Hijos', 'children'),
                Textarea::make('Nota', 'notes')
                    ->default('Sin notas')
                    ->readonly(),

            ]),
        ];
    }

    /**
     * @param HrEmployee $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }

    public function search(): array
    {
        return [
            'name'
        ];
    }
}
