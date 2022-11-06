<?php

namespace WireUi\View\Components\Buttons;

use Closure;
use WireUi\View\Components\Button;

class Mini extends Button
{
    public function __construct(
        public bool $disabledOnWireLoading = true,
        public bool $rounded = false,
        public bool $squared = false,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $color = null,
        public ?string $size = null,
    ) {
        parent::__construct(
            disabledOnWireLoading: $disabledOnWireLoading,
            block: false,
            rounded: $rounded,
            squared: $squared,
            label: $label,
            icon: $icon,
            rightIcon: null,
            color: $color,
            size: $size,
        );
    }

    protected function config(string $path): mixed
    {
        return config("wireui.buttons_mini.{$path}");
    }

    protected function proccessData(array &$data): array
    {
        $data = array_merge(parent::proccessData($data), [
            'wireLoadingAttribute' => null,
        ]);

        if ($spinner = $data['spinner']) {
            $delay = $spinner->attribute('wire:loading')->modifiers()->last();

            $data['wireLoadingAttribute'] = 'wire:loading.remove';

            if ($delay && $delay !== 'remove') {
                $data['wireLoadingAttribute'] .= ".delay.{$delay}";
            }
        }

        return $data;
    }

    public function render(): Closure
    {
        return function (array $data) {
            return view('wireui::buttons.mini', $this->proccessData($data))->render();
        };
    }
}
