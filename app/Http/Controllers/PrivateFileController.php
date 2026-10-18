<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrivateFileController extends Controller
{
    /**
     * Serve private local-disk files for panel admins.
     * Used instead of signed temporary URLs (more reliable on shared hosting).
     */
    public function __invoke(Request $request, string $path): StreamedResponse
    {
        $user = $request->user();
        abort_unless($user && $user->hasAnyRole(['super_admin', 'admin']), 403);

        $path = rawurldecode($path);
        $path = ltrim(str_replace('\\', '/', $path), '/');

        abort_if($path === '' || str_contains($path, '..'), 404);
        abort_unless(Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path, headers: [
            'Cache-Control' => 'private, max-age=300',
        ]);
    }
}
