<?php

namespace App\Http\Controllers;

use App\Models\Expo;
use App\Models\Gallery;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate([
            'expo' => ['nullable', 'integer', 'min:1', 'exists:expos,id'],
        ]);
        $selectedExpo = isset($validated['expo']) ? (int) $validated['expo'] : null;
        $expos = Expo::query()->whereHas('galleries')->orderByDesc('tanggal_mulai')->get();
        $galleries = Gallery::query()
            ->with('expo')
            ->when($selectedExpo, fn ($query) => $query->where('expo_id', $selectedExpo))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(24)
            ->withQueryString();

        return view('gallery', compact('galleries', 'expos', 'selectedExpo'));
    }
}
