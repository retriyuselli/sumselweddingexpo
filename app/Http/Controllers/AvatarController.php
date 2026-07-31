<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    /**
     * Display the specified user's avatar.
     */
    public function show(User $user)
    {
        if (! $user->avatar_url || ! Storage::exists($user->avatar_url)) {
            abort(404);
        }

        $path = Storage::path($user->avatar_url);
        $mimeType = Storage::mimeType($user->avatar_url);

        return Response::file($path, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    /**
     * Display the authenticated user's avatar.
     */
    public function showOwn(Request $request)
    {
        $user = $request->user();

        if (! $user || ! $user->avatar_url || ! Storage::exists($user->avatar_url)) {
            abort(404);
        }

        $path = Storage::path($user->avatar_url);
        $mimeType = Storage::mimeType($user->avatar_url);

        return Response::file($path, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
