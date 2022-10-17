<?php

namespace WireUi\Support\Buttons\Sizes;

class Icons extends SizePack
{
    public function default(): string
    {
        return 'w-4 h-4';
    }

    public function all(): array
    {
        return [
            '2xs' => 'w-2 h-2',
            'xs'  => 'w-3 h-3',
            'sm'  => 'w-3.5 h-3.5',
            'md'  => 'w-4 h-4',
            'lg'  => 'w-5 h-5',
            'xl'  => 'w-6 h-6',
        ];
    }
}
