<?php

namespace App\Support;

use Illuminate\Foundation\Vite as FoundationVite;

/**
 * Vite 7 serves CSS entrypoints as JS modules unless ?direct is used.
 * Filament's viteTheme() loads the theme via <link rel="stylesheet">,
 * so without ?direct the admin panel renders unstyled / "empty".
 */
class Vite extends FoundationVite
{
    protected function hotAsset($asset): string
    {
        $path = explode('?', (string) $asset)[0];
        $url = rtrim((string) file_get_contents($this->hotFile())).'/'.$path;

        if ($this->isCssPath($path)) {
            $url .= '?direct';
        }

        return $url;
    }
}
