<?php

namespace WireUi\View\Components;

use Illuminate\Support\Arr;
use Illuminate\View;

abstract class Component extends View\Component
{
    /** @deprecated use Arr::toRecursiveCssClasses instead */
    protected function classes(array $classList): string
    {
        return Arr::toCssClasses($classList);
    }
}
