<?php

namespace WireUi\View\Components;

use Illuminate\Support\Arr;
use WireUi\Support\Buttons\Colors\{Color, ColorPack};
use WireUi\Support\Buttons\Sizes\SizePack;
use WireUi\Support\Buttons\{Colors, Sizes};
use WireUi\View\Attribute;
use WireUi\View\Components\Buttons\Base;

class Button extends Base
{
    protected static string $defaultColor = 'solid';

    protected static array $colors = [
        'solid'   => Colors\Solid::class,
        'flat'    => Colors\Flat::class,
        'outline' => Colors\Outline::class,
    ];

    protected static array $sizes = [
        'base'  => Sizes\Base::class,
        'icons' => Sizes\Icons::class,
    ];

    public function __construct(
        public bool $disabledOnWireLoading = true,
        public bool $block = false,
        public bool $rounded = false,
        public bool $squared = false,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $rightIcon = null,
        public ?string $color = null,
        public ?string $iconSize = null,
        public ?string $size = null,
    ) {
        parent::__construct(
            disabledOnWireLoading: $disabledOnWireLoading,
            label: $label,
            icon: $icon,
            rightIcon: $rightIcon,
            iconSize: $iconSize,
        );
    }

    protected function init(): void
    {
        /** @var SizePack $iconSizePack */
        $iconSizePack = resolve(static::$sizes['icons']);

        $modifier = $this->getMatchModifier($iconSizePack->keys());

        $this->iconSize ??= $iconSizePack->get($modifier);
    }

    public static function setDefaultColor(string $name): void
    {
        static::$defaultColor = $name;
    }

    public static function addColor(string $color, string $class): void
    {
        static::$colors[$color] = $class;
    }

    public static function addSize(string $size, string $class): void
    {
        static::$sizes[$size] = $class;
    }

    /**
     * Apply the color modifier to the button, like hover, and focus
     *
     * @param ColorPack $colorPack
     * @param Color $color
     * @param array<int, string> $modifiers
     * @param string $event
     * @return Color
     */
    protected function applyColorModifier(
        ColorPack $colorPack,
        Color $color,
        array $modifiers,
        string $event,
    ): Color {
        /** @var Attribute $attribute */
        $attribute = $this->attributes->attribute($event);

        if (!$attribute->exists()) {
            return $color;
        }

        $modifierColor = $attribute->params()->first() ?? $attribute->value();

        foreach ($modifiers as $modifier) {
            $color->{$modifier} = $colorPack->get($modifierColor)->{$modifier};
        }

        return $color;
    }

    protected function colorPack(): ColorPack
    {
        $name = $this->getMatchModifier(array_keys(static::$colors));

        $name ??= static::$defaultColor;

        return resolve(static::$colors[$name]);
    }

    protected function currentColor(): string
    {
        $colorPack = $this->colorPack();

        $this->color ??= $this->getMatchModifier($colorPack->keys());

        $color = $colorPack->get($this->color);

        $this->applyColorModifier($colorPack, $color, ['hover'], event: 'hover');
        $this->applyColorModifier($colorPack, $color, ['focus'], event: 'focus');
        $this->applyColorModifier($colorPack, $color, ['hover', 'focus'], event: 'interaction');

        return $color;
    }

    protected function currentSize(): string
    {
        /** @var SizePack */
        $baseSize = resolve(static::$sizes['base']);

        $this->size ??= $this->getMatchModifier($baseSize->keys());

        return $baseSize->get($this->size);
    }

    protected function getCssClass(): string
    {
        return Arr::toCssClasses([
            'outline-none inline-flex justify-center items-center group hover:shadow-sm',
            'transition-all ease-in-out duration-200 focus:ring-2 focus:ring-offset-2',
            'disabled:opacity-80 disabled:cursor-not-allowed',
            'rounded-full' => !$this->squared && $this->rounded,
            'rounded'      => !$this->squared && !$this->rounded,
            'w-full'       => $this->block,
            $this->currentColor(),
            $this->currentSize(),
        ]);
    }
}
