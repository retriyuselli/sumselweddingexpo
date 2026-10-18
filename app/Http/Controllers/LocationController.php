<?php

namespace App\Http\Controllers;

use App\Services\ExpoResolver;
use Illuminate\Contracts\View\View;

class LocationController extends Controller
{
    public function __invoke(ExpoResolver $expoResolver): View
    {
        $expo = $expoResolver->nearestActive();

        return view('lokasi', compact('expo'));
    }
}
