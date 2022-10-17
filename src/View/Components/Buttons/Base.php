<?php

namespace WireUi\View\Components\Buttons;

use Closure;
use Illuminate\View\ComponentAttributeBag;
use WireUi\View\Attribute;
use WireUi\View\Components\Component;

abstract class Base extends Component
{
    public function __construct(
        public bool $disabledOnWireLoading = true,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $rightIcon = null,
        public ?string $iconSize = null,
    ) {
    }

    protected function proccessData(array $data): array
    {
        if (method_exists($this, 'init')) {
            call_user_func([$this, 'init']);
        }

        if (!$this->attributes->has('href') && !$this->attributes->has('type')) {
            $this->attributes->offsetSet('type', 'button');
        }

        if ($this->disabledOnWireLoading) {
            $this->attributes->offsetSet('wire:loading.attr', 'disabled');
            $this->attributes->offsetSet('wire:loading.class', '!cursor-wait');
        }

        return array_merge($data, [
            'iconSize'   => $this->iconSize,
            'spinner'    => $this->getSpinner(),
            'attributes' => $this->attributes->class($this->getCssClass()),
            'disabled'   => (bool) $this->attributes->get('disabled'),
            'tag'        => $this->attributes->has('href') ? 'a' : 'button',
        ]);
    }

    abstract protected function getCssClass(): string;

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

    public function render(): Closure
    {
        return function (array $data) {
            return view('wireui::button', $this->proccessData($data))->render();
        };
    }
}
