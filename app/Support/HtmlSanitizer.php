<?php

namespace App\Support;

class HtmlSanitizer
{
    /**
     * Strip dangerous HTML while keeping a small set of formatting tags.
     */
    public static function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        // Remove entire dangerous elements (with content)
        $clean = preg_replace('#<(script|iframe|object|embed|form|svg|math)[^>]*>.*?</\1>#isu', '', $html) ?? $html;
        $clean = preg_replace('#<(script|iframe|object|embed|form|svg|math)[^>]*/?>#isu', '', $clean) ?? $clean;

        $allowed = '<p><br><br/><strong><b><em><i><u><ul><ol><li><a><h2><h3><h4><blockquote><span>';
        $clean = strip_tags($clean, $allowed);

        // Remove inline event handlers (onclick, onerror, …)
        $clean = preg_replace('/\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/iu', '', $clean) ?? $clean;

        // Neutralize javascript:/data: URLs in href/src
        $clean = preg_replace(
            '/\s+(href|src)\s*=\s*([\'"])\s*(javascript|data|vbscript):[^\'"]*\2/iu',
            ' $1="#"',
            $clean
        ) ?? $clean;

        // Drop style attributes (can carry expression()/url())
        $clean = preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\')/iu', '', $clean) ?? $clean;

        return $clean;
    }
}
