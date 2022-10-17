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

    protected function getMatchModifier(array $keys): ?string
    {
        $matches = $this->attributes->only($keys)->getAttributes();

        return array_key_first($matches);
    }
}
