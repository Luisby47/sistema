<?php

declare(strict_types=1);

namespace App\MoonShine\Handlers;

use MoonShine\Exceptions\ActionException;
use MoonShine\MoonShineUI;
use Symfony\Component\HttpFoundation\Response;
use MoonShine\Handlers\Handler;

class ImportHandler extends Handler
{
    /**
     * @throws ActionException
     */
    public function handle(): Response
    {
        if (! $this->hasResource()) {
            throw new ActionException('Resource is required for action');
        }

        if ($this->isQueue()) {
            // Job here

            MoonShineUI::toast(
                __('moonshine::ui.resource.queued')
            );

            return back();
        }

        self::process();

        return back();
    }

    public static function process()
    {
        // Logic here
    }
}
