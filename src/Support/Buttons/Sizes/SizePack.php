<?php

namespace WireUi\Support\Buttons\Sizes;

abstract class SizePack
{
    public function get(?string $size): string
    {
        if ($size === null) {
            return $this->default();
        }

        return data_get(
            target: $this->all(),
            key: $size,
            default: $this->default()
        );
    }

    /** @return array<string, string> */
    public function keys(): array
    {
        return array_keys($this->all());
    }

    abstract public function default(): string;

    /** @return array<string, string> */
    abstract public function all(): array;
}
