<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;


use MoonShine\Components\MoonShineComponent;
use MoonShine\Pages\Page;

class LoginPage extends Page
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
        return $this->title ?: 'LoginPage';
    }

    /**
     * @return list<MoonShineComponent>
     */
    public function components(): array
	{
		return [];
	}
}
