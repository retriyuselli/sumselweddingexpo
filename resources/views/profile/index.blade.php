@extends('layouts.app')

@section('title', 'Profile — WeddingExpo')

@section('content')
    <main class="min-h-screen bg-gray-50">
        
        <!-- Header -->
        <section class="pt-24 md:pt-28 pb-10 bg-linear-to-r from-rose-50 to-pink-50">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar }}" 
                             alt="{{ $user->name }}" 
                             class="w-12 h-12 sm:w-16 sm:h-16 rounded-full object-cover shadow-lg border-2 border-white"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-linear-to-br from-rose-400 to-pink-600 items-center justify-center text-white font-bold text-lg sm:text-2xl shadow-lg hidden">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @else
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-linear-to-br from-rose-400 to-pink-600 flex items-center justify-center text-white font-bold text-lg sm:text-2xl shadow-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-1">{{ $user->name }}</h1>
                        <p class="text-xs sm:text-sm text-neutral-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Profile Content -->
        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                
                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-green-800 text-xs sm:text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="grid md:grid-cols-3 gap-6">
                    
                    <!-- Sidebar -->
                    <div class="md:col-span-1">
                        <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6 space-y-2">
                            <a href="#account-info" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-medium text-xs sm:text-sm">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Account Info
                            </a>
                            <a href="#security" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-neutral-50 text-neutral-700 text-xs sm:text-sm">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Security
                            </a>
                            <div class="border-t border-neutral-200 pt-2 mt-2">
                                <div class="px-3 py-2 text-xs text-neutral-500">
                                    <p class="mb-1">Member since</p>
                                    <p class="font-medium text-neutral-700">{{ $user->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="md:col-span-2 space-y-6">
                        
                        <!-- Account Information Form -->
                        <div id="account-info" class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                            <div class="mb-6">
                                <h2 class="text-base sm:text-lg font-bold mb-1">Account Information</h2>
                                <p class="text-xs text-neutral-600">Update your personal information</p>
                            </div>

                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="space-y-4 sm:space-y-5">
                                    
                                    <!-- Profile Photo Upload -->
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 mb-2">
                                            Profile Photo
                                        </label>
                                        <div class="flex items-center gap-4">
                                            @if($user->avatar_url)
                                                <img src="{{ $user->avatar }}" 
                                                     alt="{{ $user->name }}" 
                                                     id="avatar-preview"
                                                     class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-neutral-200"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                                <div id="avatar-fallback" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-linear-to-br from-rose-400 to-pink-600 items-center justify-center text-white font-bold text-xl sm:text-2xl hidden">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @else
                                                <div id="avatar-preview-container" class="relative">
                                                    <img src="" 
                                                         alt="Avatar preview" 
                                                         id="avatar-preview"
                                                         class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-neutral-200 hidden">
                                                    <div id="avatar-fallback" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-linear-to-br from-rose-400 to-pink-600 flex items-center justify-center text-white font-bold text-xl sm:text-2xl">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="flex-1">
                                                <input type="file" 
                                                       id="avatar_url" 
                                                       name="avatar_url" 
                                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                                       class="hidden"
                                                       onchange="previewAvatar(event)">
                                                <label for="avatar_url" 
                                                       class="inline-block px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-medium rounded-lg cursor-pointer transition text-xs sm:text-sm">
                                                    Choose Photo
                                                </label>
                                                <p class="mt-1 text-xs text-neutral-500">JPG, PNG, GIF or WebP (max 2MB)</p>
                                                @error('avatar_url')
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Name -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">
                                            Full Name
                                        </label>
                                        <input type="text" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $user->name) }}"
                                               class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg text-xs sm:text-sm"
                                               required>
                                        @error('name')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">
                                            Email Address
                                        </label>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $user->email) }}"
                                               class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg text-xs sm:text-sm"
                                               required>
                                        @error('email')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Role (Read Only) -->
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 mb-2">
                                            Role
                                        </label>
                                        <div class="px-4 py-2.5 bg-neutral-50 border border-neutral-200 rounded-lg text-xs sm:text-sm text-neutral-600">
                                            {{ $user->role_name }}
                                        </div>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="pt-2">
                                        <button type="submit" 
                                                class="w-full sm:w-auto px-6 py-2.5 bg-rose-600 text-white font-semibold rounded-lg hover:bg-rose-700 transition text-xs sm:text-sm">
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Security / Change Password -->
                        <div id="security" class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                            <div class="mb-6">
                                <h2 class="text-base sm:text-lg font-bold mb-1">Change Password</h2>
                                <p class="text-xs text-neutral-600">Update your password to keep your account secure</p>
                            </div>

                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PUT')
                                
                                <input type="hidden" name="name" value="{{ $user->name }}">
                                <input type="hidden" name="email" value="{{ $user->email }}">
                                
                                <div class="space-y-4 sm:space-y-5">
                                    <!-- Current Password -->
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-neutral-700 mb-2">
                                            Current Password
                                        </label>
                                        <input type="password" 
                                               id="current_password" 
                                               name="current_password" 
                                               class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg text-xs sm:text-sm">
                                        @error('current_password')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- New Password -->
                                    <div>
                                        <label for="new_password" class="block text-sm font-medium text-neutral-700 mb-2">
                                            New Password
                                        </label>
                                        <input type="password" 
                                               id="new_password" 
                                               name="new_password" 
                                               class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg text-xs sm:text-sm">
                                        @error('new_password')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-neutral-500">Minimal 8 karakter</p>
                                    </div>

                                    <!-- Confirm New Password -->
                                    <div>
                                        <label for="new_password_confirmation" class="block text-sm font-medium text-neutral-700 mb-2">
                                            Confirm New Password
                                        </label>
                                        <input type="password" 
                                               id="new_password_confirmation" 
                                               name="new_password_confirmation" 
                                               class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg text-xs sm:text-sm">
                                    </div>

                                    <!-- Update Password Button -->
                                    <div class="pt-2">
                                        <button type="submit" 
                                                class="w-full sm:w-auto px-6 py-2.5 bg-rose-600 text-white font-semibold rounded-lg hover:bg-rose-700 transition text-xs sm:text-sm">
                                            Update Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Danger Zone -->
                        <div class="bg-white rounded-xl border border-red-200 p-4 sm:p-6">
                            <div class="mb-4">
                                <h2 class="text-base sm:text-lg font-bold text-red-600 mb-1">Danger Zone</h2>
                                <p class="text-xs text-neutral-600">Irreversible actions</p>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 bg-red-50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-xs sm:text-sm text-neutral-900">Delete Account</h3>
                                    <p class="text-xs text-neutral-600 mt-1">Once deleted, your account cannot be recovered</p>
                                </div>
                                <button class="px-4 py-2 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition whitespace-nowrap">
                                    Delete Account
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </main>

    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            console.log('Avatar file selected:', file);
            
            if (file) {
                // Validasi ukuran file (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File terlalu besar! Maksimal 2MB');
                    event.target.value = '';
                    return;
                }

                // Validasi tipe file
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak valid! Gunakan JPG, PNG, GIF, atau WebP');
                    event.target.value = '';
                    return;
                }

                console.log('Avatar validation passed, showing preview');
                
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    const fallback = document.getElementById('avatar-fallback');
                    
                    if (preview && fallback) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        fallback.classList.add('hidden');
                        console.log('Avatar preview updated');
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
