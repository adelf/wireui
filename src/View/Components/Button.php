<?php

namespace WireUi\View\Components;

use WireUi\Support\Buttons\Colors\{Color, ColorPack};
use WireUi\Support\Buttons\Sizes\SizePack;
use WireUi\Support\Buttons\{Colors, Sizes};
use WireUi\Traits\HasModifiers;
use WireUi\View\Attribute;
use WireUi\View\Components\Buttons\Base;

class Button extends Base
{
    use HasModifiers;

    protected static array $colors = [
        'solid'   => Colors\Solid::class,
        'flat'    => Colors\Flat::class,
        'outline' => Colors\Outline::class,
    ];

    protected static array $sizes = [
        'base' => Sizes\Base::class,
        'icon' => Sizes\Icon::class,
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
        public ?string $size = null,
        public ?string $iconSize = null,
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
        $this->setupSize();
        $this->setupIconSize();
        $this->setupColor();
    }

    protected function setupSize(): self
    {
        /** @var SizePack $sizePack */
        $sizePack = resolve(static::$sizes['base']);

        $this->size ??= $this->getMatchModifier($sizePack->keys());
        $this->size ??= config('wireui.button.size');

        $this->attributes = $this->attributes->class(
            $sizePack->get($this->size)
        );

        return $this;
    }

    protected function setupIconSize(): self
    {
        /** @var SizePack $sizePack */
        $sizePack = resolve(static::$sizes['icon']);

        $modifier = $this->iconSize ?? $this->size;

        $this->iconSize = $sizePack->get($modifier);

        return $this;
    }

    protected function setupColor(): self
    {
        $this->attributes = $this->attributes->class([
            'outline-none inline-flex justify-center items-center group hover:shadow-sm',
            'transition-all ease-in-out duration-200 focus:ring-2 focus:ring-offset-2',
            'disabled:opacity-80 disabled:cursor-not-allowed',
            'rounded-full' => $this->shouldBePill(),
            'rounded'      => $this->shouldBeRounded(),
            'w-full'       => $this->block,
            $this->getCurrentColor(),
        ]);

        return $this;
    }

    protected function shouldBePill(): bool
    {
        return !$this->squared && $this->rounded;
    }

    protected function shouldBeRounded(): bool
    {
        return !$this->squared && !$this->rounded;
    }

    protected function getColorPack(): ColorPack
    {
        $style = $this->getMatchModifier(array_keys(static::$colors));

        $style ??= config('wireui.button.style');

        return resolve(static::$colors[$style]);
    }

    protected function getCurrentColor(): string
    {
        $colorPack = $this->getColorPack();

        $this->color ??= $this->getMatchModifier($colorPack->keys());
        $this->color ??= config('wireui.button.color');

        $color = $colorPack->get($this->color);

        $this->applyColorModifier($colorPack, $color, ['hover'], event: 'hover');
        $this->applyColorModifier($colorPack, $color, ['focus'], event: 'focus');
        $this->applyColorModifier($colorPack, $color, ['hover', 'focus'], event: 'interaction');

        return $color;
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
}
