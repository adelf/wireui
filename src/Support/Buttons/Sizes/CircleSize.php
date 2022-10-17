<?php

namespace WireUi\Support\Buttons\Sizes;

class CircleSize extends SizePack
{
    public function default(): string
    {
        return 'w-9 h-9';
    }

    public function all(): array
    {
        return [
            '2xs' => 'w-5 h-5',
            'xs'  => 'w-7 h-7',
            'sm'  => 'w-8 h-8',
            'md'  => 'w-10 h-10',
            'lg'  => 'w-12 h-12',
            'xl'  => 'w-14 h-14',
        ];
    }
}
