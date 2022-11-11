<{{ $tag }} {{ $attributes }}>
    @if ($icon)
        <x-dynamic-component
            :component="WireUi::component('icon')"
            class="{{ $iconSize }} shrink-0"
            :name="$icon"
        />
    @endif

    {{ $label ?? $slot }}

    @if ($rightIcon)
        <x-dynamic-component
            :component="WireUi::component('icon')"
            class="{{ $iconSize }} shrink-0"
            :wire:loading.remove="(bool) $spinner"
            :name="$rightIcon"
        />
    @endif

    @if ($spinner)
        <x-phosphor.icons::regular.circle-notch
            class="animate-spin {{ $iconSize }} shrink-0"
            {{ $spinner }}
        />
    @endif
</{{ $tag }}>
