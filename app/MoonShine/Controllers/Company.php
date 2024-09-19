<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use MoonShine\MoonShineRequest;
use MoonShine\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class Company extends MoonShineController
{
    public function __invoke(MoonShineRequest $request): Response
    {
        // Examples

        // $this->toast('Hello world');
        // $request->getPage();
        // $request->getResource();

        /*
        // Render custom content
        return $this
            ->view('path_to_blade', ['param' => 'value'])
            ->setLayout('custom_layout')
            ->render();
        */

        return back();
    }
}
