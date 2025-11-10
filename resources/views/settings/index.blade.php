@extends('layouts.app')

@section('title', 'Settings — WeddingExpo')

@section('content')
    <main class="min-h-screen bg-gray-50">
        
        <!-- Header -->
        <section class="pt-24 md:pt-28 pb-10 bg-gradient-to-r from-rose-50 to-pink-50">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-1">Settings</h1>
                        <p class="text-xs sm:text-sm text-neutral-600">Manage your account preferences</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Settings Content -->
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
                            <a href="#notifications" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-purple-50 text-purple-600 font-medium text-xs sm:text-sm">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                Notifications
                            </a>
                            <a href="#privacy" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-neutral-50 text-neutral-700 text-xs sm:text-sm">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Privacy
                            </a>
                            <a href="#appearance" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-neutral-50 text-neutral-700 text-xs sm:text-sm">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                </svg>
                                Appearance
                            </a>
                            <div class="border-t border-neutral-200 pt-2 mt-2">
                                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-neutral-50 text-neutral-600 text-xs sm:text-sm">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Back to Profile
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="md:col-span-2 space-y-6">
                        
                        <!-- Notifications Settings -->
                        <div id="notifications" class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                            <div class="mb-6">
                                <h2 class="text-base sm:text-lg font-bold mb-1">Notification Preferences</h2>
                                <p class="text-xs text-neutral-600">Choose how you want to be notified</p>
                            </div>

                            <form method="POST" action="{{ route('settings.update') }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="space-y-4">
                                    <!-- Email Notifications -->
                                    <div class="flex items-start justify-between p-4 bg-neutral-50 rounded-lg">
                                        <div class="flex-1">
                                            <h3 class="text-xs sm:text-sm font-medium text-neutral-900 mb-1">Email Notifications</h3>
                                            <p class="text-xs text-neutral-600">Receive notifications via email</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer ml-4">
                                            <input type="checkbox" name="notifications_email" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        </label>
                                    </div>

                                    <!-- SMS Notifications -->
                                    <div class="flex items-start justify-between p-4 bg-neutral-50 rounded-lg">
                                        <div class="flex-1">
                                            <h3 class="text-xs sm:text-sm font-medium text-neutral-900 mb-1">SMS Notifications</h3>
                                            <p class="text-xs text-neutral-600">Receive notifications via SMS</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer ml-4">
                                            <input type="checkbox" name="notifications_sms" class="sr-only peer">
                                            <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        </label>
                                    </div>

                                    <!-- Event Updates -->
                                    <div class="flex items-start justify-between p-4 bg-neutral-50 rounded-lg">
                                        <div class="flex-1">
                                            <h3 class="text-xs sm:text-sm font-medium text-neutral-900 mb-1">Event Updates</h3>
                                            <p class="text-xs text-neutral-600">Get notified about new events and updates</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer ml-4">
                                            <input type="checkbox" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        </label>
                                    </div>

                                    <!-- Marketing Emails -->
                                    <div class="flex items-start justify-between p-4 bg-neutral-50 rounded-lg">
                                        <div class="flex-1">
                                            <h3 class="text-xs sm:text-sm font-medium text-neutral-900 mb-1">Marketing Emails</h3>
                                            <p class="text-xs text-neutral-600">Receive promotional emails and special offers</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer ml-4">
                                            <input type="checkbox" class="sr-only peer">
                                            <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition text-xs sm:text-sm">
                                        Save Preferences
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Privacy Settings -->
                        <div id="privacy" class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                            <div class="mb-6">
                                <h2 class="text-base sm:text-lg font-bold mb-1">Privacy Settings</h2>
                                <p class="text-xs text-neutral-600">Control your privacy and data</p>
                            </div>

                            <form method="POST" action="{{ route('settings.update') }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="space-y-4">
                                    <!-- Show Email -->
                                    <div class="flex items-start justify-between p-4 bg-neutral-50 rounded-lg">
                                        <div class="flex-1">
                                            <h3 class="text-xs sm:text-sm font-medium text-neutral-900 mb-1">Show Email Address</h3>
                                            <p class="text-xs text-neutral-600">Allow others to see your email address</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer ml-4">
                                            <input type="checkbox" name="privacy_show_email" class="sr-only peer">
                                            <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        </label>
                                    </div>

                                    <!-- Public Profile -->
                                    <div class="flex items-start justify-between p-4 bg-neutral-50 rounded-lg">
                                        <div class="flex-1">
                                            <h3 class="text-xs sm:text-sm font-medium text-neutral-900 mb-1">Public Profile</h3>
                                            <p class="text-xs text-neutral-600">Make your profile visible to other users</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer ml-4">
                                            <input type="checkbox" name="privacy_show_profile" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        </label>
                                    </div>

                                    <!-- Search Engine Indexing -->
                                    <div class="flex items-start justify-between p-4 bg-neutral-50 rounded-lg">
                                        <div class="flex-1">
                                            <h3 class="text-xs sm:text-sm font-medium text-neutral-900 mb-1">Search Engine Indexing</h3>
                                            <p class="text-xs text-neutral-600">Allow search engines to index your profile</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer ml-4">
                                            <input type="checkbox" class="sr-only peer">
                                            <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition text-xs sm:text-sm">
                                        Save Privacy Settings
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Appearance Settings -->
                        <div id="appearance" class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                            <div class="mb-6">
                                <h2 class="text-base sm:text-lg font-bold mb-1">Appearance</h2>
                                <p class="text-xs text-neutral-600">Customize how the site looks</p>
                            </div>

                            <div class="space-y-4">
                                <!-- Theme Selection -->
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-neutral-700 mb-3">Theme</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div class="relative">
                                            <input type="radio" name="theme" id="light" class="sr-only peer" checked>
                                            <label for="light" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-neutral-200 rounded-lg cursor-pointer peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:bg-neutral-50">
                                                <svg class="w-6 h-6 mb-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                                <span class="text-xs font-medium">Light</span>
                                            </label>
                                        </div>
                                        <div class="relative">
                                            <input type="radio" name="theme" id="dark" class="sr-only peer">
                                            <label for="dark" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-neutral-200 rounded-lg cursor-pointer peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:bg-neutral-50">
                                                <svg class="w-6 h-6 mb-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                                </svg>
                                                <span class="text-xs font-medium">Dark</span>
                                            </label>
                                        </div>
                                        <div class="relative">
                                            <input type="radio" name="theme" id="auto" class="sr-only peer">
                                            <label for="auto" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-neutral-200 rounded-lg cursor-pointer peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:bg-neutral-50">
                                                <svg class="w-6 h-6 mb-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                                </svg>
                                                <span class="text-xs font-medium">Auto</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Language Selection -->
                                <div>
                                    <label for="language" class="block text-xs sm:text-sm font-medium text-neutral-700 mb-2">Language</label>
                                    <select id="language" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg text-xs sm:text-sm">
                                        <option value="id" selected>Bahasa Indonesia</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>

                                <div class="mt-6">
                                    <button type="button" class="w-full sm:w-auto px-6 py-2.5 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition text-xs sm:text-sm">
                                        Save Appearance
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
