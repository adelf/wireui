<?php

namespace WireUi\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;
use WireUi\Actions\Minify;

class BladeDirectives
{
    protected string $nullableLivewireId = "<?= isset(\$_instance) ? \"'{\$_instance->id}'\" : 'null' ?>";

    public function scripts(bool $absolute = true, array $attributes = []): string
    {
        $route   = route(name: 'wireui.assets.scripts', absolute: $absolute);
        $version = $this->getManifestVersion('wireui.js');

        if ($version) {
            $route .= "?id={$version}";
        }

        $attributes = new ComponentAttributeBag($attributes);

        return <<<HTML
        <script {$attributes->toHtml()}>{$this->hooksScript()}</script>
        <script src="{$route}" defer {$attributes->toHtml()}></script>
        HTML;
    }

    public function hooksScript(): string
    {
        $scripts = <<<JS
            window.Wireui = {
                hook(hook, callback) {
                    window.addEventListener(`wireui:\${hook}`, () => callback())
                },
                dispatchHook(hook) {
                    window.dispatchEvent(new Event(`wireui:\${hook}`))
                }
            }
        JS;

        return Minify::execute($scripts);
    }

    public function styles(bool $absolute = true): string
    {
        $route   = route(name: 'wireui.assets.styles', absolute: $absolute);
        $version = $this->getManifestVersion('wireui.css');

        if ($version) {
            $route .= "?id={$version}";
        }

        return "<link href=\"{$route}\" rel=\"stylesheet\" type=\"text/css\">";
    }

    public function getManifestVersion(string $file): ?string
    {
        $manifestPath = dirname(__DIR__, 2) . '/dist/mix-manifest.json';

        if (File::missing($manifestPath)) {
            return null;
        }

        $manifest = json_decode(file_get_contents($manifestPath), $assoc = true);
        $version  = last(explode('=', $manifest["/{$file}"]));

        return $version;
    }

    public function confirmAction(string $expression): string
    {
        return "onclick=\"\$wireui.confirmAction({$expression}, {$this->nullableLivewireId})\"";
    }

    public function notify(string $expression): string
    {
        return "onclick=\"\$wireui.notify({$expression}, {$this->nullableLivewireId})\"";
    }

    public function boolean(string $value): string
    {
        return "<?= json_encode(filter_var({$value}, FILTER_VALIDATE_BOOLEAN)); ?>";
    }

    public function entangleable(string $expression): ?string
    {
        $fallback = (string) Str::of($expression)->after(',')->trim();
        $property = (string) Str::of($expression)->before(',')->trim();

        return <<<EOT
        <?php if (!isset(\$_instance->id)): ?> @toJs({$fallback}) <?php else : ?> @entangle({$property}) <?php endif; ?>
        EOT;
    }
}
