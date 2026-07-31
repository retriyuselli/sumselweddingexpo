<?php

namespace App\Http\Controllers;

use App\Models\Penyelenggara;
use App\Models\PenyelenggaraGallery;
use App\Models\User;

class PenyelenggaraController extends Controller
{
    /**
     * Tampilkan halaman penyelenggara publik.
     */
    public function index()
    {
        $penyelenggara = Penyelenggara::with([
            'galleries' => fn($q) => $q->published()->ordered(),
        ])->orderBy('name')->first();

        $featuredGalleries = $penyelenggara
            ? PenyelenggaraGallery::published()
                ->featured()
                ->ordered()
                ->where('penyelenggara_id', $penyelenggara->id)
                ->take(12)
                ->get()
            : collect();

        $teamMembers = User::withRole('swe')->orderBy('name')->get();

        return view('penyelenggara', compact('penyelenggara', 'featuredGalleries', 'teamMembers'));
    }
}
