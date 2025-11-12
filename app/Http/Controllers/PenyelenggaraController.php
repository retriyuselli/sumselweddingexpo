<?php

namespace App\Http\Controllers;

use App\Models\Penyelenggara;
use App\Models\PenyelenggaraGallery;
use Illuminate\Http\Request;

class PenyelenggaraController extends Controller
{
    /**
     * Tampilkan halaman penyelenggara publik.
     */
    public function index()
    {
        // Ambil satu-satunya penyelenggara beserta galerinya (yang published dan terurut)
        $single = Penyelenggara::with([
            'galleries' => function ($q) {
                $q->published()->ordered();
            },
        ])->orderBy('name')->first();

        // Bungkus sebagai koleksi untuk kompatibilitas view yang sudah ada
        $penyelenggaras = $single ? collect([$single]) : collect();

        // Ambil galeri unggulan hanya milik penyelenggara utama
        $featuredGalleries = $single
            ? PenyelenggaraGallery::published()
                ->featured()
                ->ordered()
                ->where('penyelenggara_id', $single->id)
                ->take(12)
                ->get()
            : collect();

        return view('penyelenggara', compact('penyelenggaras', 'featuredGalleries'));
    }
}