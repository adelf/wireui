<{{ $tag }} {{ $attributes }}>
    <div class="shrink-0" {{ $wireLoadingAttribute }}>
        @if ($icon)
            <x-dynamic-component
                :component="WireUi::component('icon')"
                class="{{ $iconSize }} shrink-0"
                :name="$icon"
            />
        @else
            {{ $label ?? $slot }}
        @endif
    </div>

    @if ($spinner)
        <x-phosphor.icons::regular.circle-notch
            class="animate-spin {{ $iconSize }} shrink-0"
            {{ $spinner }}
        />
    @endif
</{{ $tag }}>
