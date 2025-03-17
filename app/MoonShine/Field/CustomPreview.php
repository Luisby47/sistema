<?php

namespace App\MoonShine\Field;

use MoonShine\Contracts\Fields\DefaultValueTypes\DefaultCanBeNumeric;
use MoonShine\Contracts\Fields\HasDefaultValue;
use MoonShine\Contracts\Fields\HasUpdateOnPreview;
use MoonShine\Contracts\HasReactivity;
use MoonShine\Fields\Fields;
use MoonShine\Traits\Fields\Reactivity;

    class CustomPreview extends \MoonShine\Fields\Preview implements HasReactivity
{
        use Reactivity;

}
