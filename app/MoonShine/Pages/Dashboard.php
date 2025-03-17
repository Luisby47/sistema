<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;


use App\Models\ResCompany;
use MoonShine\Components\FieldsGroup;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Components\Title;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Heading;
use MoonShine\Fields\Template;
use MoonShine\Fields\Text;
use MoonShine\Pages\Page;

class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Inicio';
    }

    /**
     * @return list<MoonShineComponent>
     */
    public function components(): array
	{

        $companyName = $companyName = ResCompany::query()->value('name');
		return [
            Heading::make('¡Bienvenido al Sistema de Planillas de WG!')->h(1, false),
            Heading::make( 'La empresa actual es  ' . $companyName)->h(2, false),

            
        ];
	}
}
