<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\HrDepartment;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResCompany;

use MoonShine\Fields\Date;
use MoonShine\Fields\Email;
use MoonShine\Fields\Number;
use MoonShine\Fields\Phone;
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

/**
 * @extends ModelResource<ResCompany>
 */
class ResCompanyResource extends ModelResource
{
    protected string $model = ResCompany::class;

    protected string $title = 'Empresas';

    // Se define el campo de la tabla que se va a mostrar por referencia o operaciones
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
                ID::make()->sortable()->hideOnAll(),
                Text::make('Cedula', 'company_registry'),
                Text::make('Nombre', 'name'),
                Date::make('Fecha de creación', 'create_date')
                    ->format('d/m/Y')
                    ->default(now()->toDateTimeString())
                    ->sortable(),
                //logo_web byte logo de la compañia
                Email::make('Correo', 'email'),
                Phone::make('Teléfono', 'phone')->mask('9999-9999'),
                Number::make('Numero de Cuenta', 'account_no'),
                // Referencia al identificador del diario de cambio de moneda en la tabla
                //account_journal.id

                //BelongsTo::make('Diario de Cambio de Moneda', 'currency_exchange_journal', static fn (ResCompany $model) => $model->name, new ResCompanyResource()),
                //Number::make( 'Cambio de Moneda', 'currency_exchange_journal_id'),


            ]),
        ];
    }

    /**
     * @param ResCompany $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
