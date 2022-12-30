@php
    $hasError  = !$errorless && $name && $errors->has($name);
    $hasCorner = isset($corner) || $cornerHint;
@endphp

<div class="peer-disabled:opacity-60">
    @if ($label || $corner)
        <div class="flex flex-row-reverse justify-between mb-1">
            @if ($label)
                <x-dynamic-component
                    :component="WireUi::component('label')"
                    :has-error="$hasError"
                    :for="$id"
                >
                    {{ $label }}
                </x-dynamic-component>
            @endif

            @if ($cornerHint)
                <x-dynamic-component
                    :component="WireUi::component('label')"
                    :has-error="$hasError"
                    :for="$id"
                >
                    {{ $cornerHint }}
                </x-dynamic-component>
            @elseif (isset($corner))
                {{ $corner }}
            @endif
        </div>
    @endif

    <label
        @class([
            'relative rounded-md',
            'shadow-sm' => !$shadowless,
            'form-input',
            'flex gap-x-1.5 relative mt-1 rounded-md shadow-sm',
            'border-gray-300',
            'focus-within:border-indigo-600',
            'focus-within:ring-indigo-600',
            'focus-within:ring-1',
        ])
        @if ($id) for="{{ $id }}" @endif
    >
        <div class="flex items-center">
            <span class="text-gray-500 sm:text-sm">http://</span>
        </div>

        {{ $slot }}

        <div class="flex items-center">
            <button class="focus:ring-2 focus:bg-red-500 text-gray-500 sm:text-sm">http://</button>
        </div>
    </label>

    <div
        @class([
            'relative rounded-md',
            'shadow-sm' => !$shadowless,
        ])
    >
        @if ($prefix || $icon)
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none
                {{ $hasError ? 'text-negative-500' : 'text-secondary-400' }}">
                @if ($icon)
                    <x-dynamic-component
                        :component="WireUi::component('icon')"
                        :name="$icon"
                        class="w-5 h-5"
                    />
                @elseif($prefix)
                    <span class="flex items-center self-center pl-1">
                        {{ $prefix }}
                    </span>
                @endif
            </div>
        @elseif (isset($prepend))
            {{ $prepend }}
        @endif

        {{ $slot }}

        @if ($suffix || $rightIcon || ($hasError && !$append))
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none
                {{ $hasError ? 'text-negative-500' : 'text-secondary-400' }}">
                @if ($rightIcon)
                    <x-dynamic-component
                        :component="WireUi::component('icon')"
                        :name="$rightIcon"
                        class="w-5 h-5"
                    />
                @elseif ($suffix)
                    <span class="flex items-center justify-center pr-1">
                        {{ $suffix }}
                    </span>
                @elseif ($hasError)
                    <x-dynamic-component
                        :component="WireUi::component('icon')"
                        name="exclamation-circle"
                        class="w-5 h-5"
                    />
                @endif
            </div>
        @elseif (isset($append))
            {{ $append }}
        @endif
    </div>

    @if (!$hasError && $description)
        <label
            class="mt-2 text-sm text-secondary-500 dark:text-secondary-400"
            @if ($id) for="{{ $id }}" @endif
        >
            {{ $description }}
        </label>
    @endif

    @if ($name && !$errorless)
        <x-dynamic-component
            :component="WireUi::component('error')"
            :custom="$errorMessage"
            :name="$name"
        />
    @endif
</div>
