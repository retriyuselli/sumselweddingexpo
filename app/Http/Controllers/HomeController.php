<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expo;

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
        
        // Daftar logo sponsor & exhibitor (nama dan file). 
        // Letakkan file logo di public/images/sponsors dan public/images/exhibitors.
        $sponsors = [
            ['name' => 'Brand A', 'file' => 'brand-a.png'],
            ['name' => 'Brand B', 'file' => 'brand-b.png'],
            ['name' => 'Brand C', 'file' => 'brand-c.png'],
            ['name' => 'Brand D', 'file' => 'brand-d.png'],
            ['name' => 'Brand E', 'file' => 'brand-e.png'],
            ['name' => 'Brand F', 'file' => 'brand-f.png'],
        ];

        $exhibitors = [
            ['name' => 'Vendor 1', 'file' => 'vendor-1.png'],
            ['name' => 'Vendor 2', 'file' => 'vendor-2.png'],
            ['name' => 'Vendor 3', 'file' => 'vendor-3.png'],
            ['name' => 'Vendor 4', 'file' => 'vendor-4.png'],
            ['name' => 'Vendor 5', 'file' => 'vendor-5.png'],
            ['name' => 'Vendor 6', 'file' => 'vendor-6.png'],
            ['name' => 'Vendor 7', 'file' => 'vendor-7.png'],
            ['name' => 'Vendor 8', 'file' => 'vendor-8.png'],
            ['name' => 'Vendor 9', 'file' => 'vendor-9.png'],
            ['name' => 'Vendor 10', 'file' => 'vendor-10.png'],
            ['name' => 'Vendor 11', 'file' => 'vendor-11.png'],
            ['name' => 'Vendor 12', 'file' => 'vendor-12.png'],
        ];

        return view('home', compact('eventDate', 'eventStart', 'eventEnd', 'eventLocation', 'eventName', 'expo', 'sponsors', 'exhibitors'));
    }
}
