<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Vendor;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function profile()
    {
        return view('profile.index', [
            'user' => Auth::user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar_url')) {
            Log::info('Avatar upload detected', [
                'user_id' => $user->id,
                'old_avatar' => $user->avatar_url,
                'file_size' => $request->file('avatar_url')->getSize(),
                'mime_type' => $request->file('avatar_url')->getMimeType()
            ]);
            
            // Delete old avatar if exists
            if ($user->avatar_url) {
                Storage::disk('public')->delete($user->avatar_url);
                Log::info('Old avatar deleted', ['path' => $user->avatar_url]);
            }

            // Store new avatar in public storage
            $avatarPath = $request->file('avatar_url')->store('avatars', 'public');
            $user->avatar_url = $avatarPath;
            
            Log::info('New avatar saved', [
                'path' => $avatarPath,
                'exists' => Storage::disk('public')->exists($avatarPath)
            ]);
        } else {
            Log::info('No avatar file in request', [
                'has_avatar_input' => $request->has('avatar_url'),
                'all_files' => array_keys($request->allFiles())
            ]);
        }

        // Update name and email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Update password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // Force session regeneration to clear any cached data
        $request->session()->regenerate();

        return redirect()->route('profile')->with('success', 'Profile berhasil diperbarui!');
    }

    public function settings()
    {
        return view('settings.index', [
            'user' => Auth::user()
        ]);
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'notifications_email' => ['nullable', 'boolean'],
            'notifications_sms' => ['nullable', 'boolean'],
            'privacy_show_email' => ['nullable', 'boolean'],
            'privacy_show_profile' => ['nullable', 'boolean'],
        ]);

        // Here you would typically save these settings to a settings table
        // For now, we'll just return success
        
        return back()->with('success', 'Settings berhasil diperbarui!');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $registeredAsExhibitor = Vendor::where('email', $user->email)->exists();

        return view('dashboard.index', [
            'user' => $user,
            'registeredAsExhibitor' => $registeredAsExhibitor,
        ]);
    }
}
