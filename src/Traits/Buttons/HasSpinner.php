<?php

namespace WireUi\Traits\Buttons;

use Illuminate\View\{Component, ComponentAttributeBag};
use WireUi\View\Attribute;

/** @mixin Component */
trait HasSpinner
{
    protected function getSpinner(): ?ComponentAttributeBag
    {
        /** @var Attribute $spinner */
        $spinner = $this->attributes->attribute('spinner');

        if (!$spinner->exists()) {
            return null;
        }

        $target  = $spinner->value();
        $loading = 'wire:loading.delay';

        if ($delay = $spinner->modifiers()->first()) {
            $loading .= ".{$delay}";
        }

        $attributes = new ComponentAttributeBag([$loading => 'true']);

        if (is_string($target)) {
            $attributes->offsetSet('wire:target', $target);
        }

        $this->attributes->offsetUnset($spinner->directive());

        return $attributes;
    }
}
