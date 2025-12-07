@extends('layouts.normal')

@section('title', 'Settings')

@section('chat')

<div class="w-full max-w-4xl mx-auto py-8 px-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Settings</h1>
    <p class="text-gray-600 mb-8">Manage your account and preferences</p>

    <!-- Settings Sections -->
    <div class="space-y-6">
        <!-- Account Settings -->
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-blue-600">person</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Account Settings</h2>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" value="{{ auth('normaluser')->user()->name }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" value="{{ auth('normaluser')->user()->email }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" value="{{ auth('normaluser')->user()->mobile }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                </div>

                <button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition duration-200">Edit Profile</button>
            </div>
        </div>

        <!-- Preferences -->
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-purple-600">tune</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Preferences</h2>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">Dark Mode</p>
                        <p class="text-sm text-gray-600">Toggle dark theme</p>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-5 h-5">
                        <span class="text-sm font-medium">Enable</span>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">Email Notifications</p>
                        <p class="text-sm text-gray-600">Receive updates via email</p>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-5 h-5" checked>
                        <span class="text-sm font-medium">Enabled</span>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">Save Chat History</p>
                        <p class="text-sm text-gray-600">Keep your conversations</p>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-5 h-5" checked>
                        <span class="text-sm font-medium">Enabled</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Security -->
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-red-600">security</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Security</h2>
            </div>

            <div class="space-y-3">
                <button class="w-full px-6 py-3 border border-gray-300 hover:border-gray-400 text-gray-900 rounded-lg font-medium transition duration-200">
                    Change Password
                </button>
                <button class="w-full px-6 py-3 border border-red-300 text-red-600 hover:bg-red-50 rounded-lg font-medium transition duration-200">
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
