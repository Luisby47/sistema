<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;


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
		return [
            Heading::make('¡Bienvenido al Sistema de Planillas de WG!')->h(1, false),

        ];
	}
}
