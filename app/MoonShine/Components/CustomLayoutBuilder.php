<?php

declare(strict_types=1);

namespace App\MoonShine\Components;

use Closure;
use Illuminate\Contracts\View\View;
use MoonShine\Components\MoonShineComponent;

/**
 * @method static static make()
 */
final class CustomLayoutBuilder extends MoonShineComponent
{
    protected string $view = 'admin.components.custom-layout-builder';

    public function __construct()
    {
        //
    }

    /*
     * @return array<string, mixed>
     */
    protected function viewData(): array
    {
        return [];
    }
}
