<?php

namespace App\Http\Controllers;

use App\Models\Expo;
use App\Models\Home;
use App\Models\Sponsor;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil data expo yang aktif (status = 1) pertama
        $expo = Expo::where('status', 1)->first();

        // Jika ada expo aktif, gunakan tanggal_mulai nya, jika tidak fallback ke default
        $eventDate = $expo ? $expo->tanggal_mulai->format('Y-m-d H:i:s') : '2026-01-16 00:00:00';
        $eventStart = $expo ? $expo->tanggal_mulai : null;
        $eventEnd = $expo ? $expo->tanggal_selesai : null;
        $eventLocation = $expo ? $expo->lokasi : 'Grand City Surabaya Convention Hall';
        $eventName = $expo ? $expo->nama_expo : 'Wedding Expo 2026';

        // Ambil data home page dari database
        $home = Home::active()->first();
        if (! $home) {
            $home = new Home([
                'tentang_kami' => 'Selamat datang di Sumatra Wedding Expo',
                'hero_subtitle' => 'Temukan Vendor Pernikahan Impian Anda',
                'highlight_videos' => [],
                'is_active' => true,
            ]);
        }

        // Ambil data sponsor yang aktif dan memiliki logo, diurutkan berdasarkan order
        $sponsors = Sponsor::where('is_active', true)
            ->whereNotNull('logo')
            ->where('logo', '!=', '')
            ->orderBy('order')
            ->get();

        return view('home', compact('eventDate', 'eventStart', 'eventEnd', 'eventLocation', 'eventName', 'expo', 'home', 'sponsors'));
    }
}
