<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function notice(Request $request)
    {
        if ($request->user()?->hasVerifiedEmail()) {
            return redirect()->intended('/');
        }

        return view('auth.verify-email', [
            'user' => $request->user(),
        ]);
    }
}
