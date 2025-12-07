@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="mb-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Welcome, Admin! 👋</h1>
    <p class="text-gray-600 text-lg">Manage your platform, users, and settings from this dashboard.</p>
</div>

<!-- Dashboard Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <!-- Users Card -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-8 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Manage Users</h3>
            <span class="material-icons text-4xl opacity-30">people</span>
        </div>
        <p class="text-4xl font-bold mb-6">Users</p>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-2 px-4 rounded-lg font-medium transition duration-200">
            <span class="material-icons text-lg">arrow_forward</span>
            View All Users
        </a>
    </div>

    <!-- Create User Card -->
    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg p-8 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Add New User</h3>
            <span class="material-icons text-4xl opacity-30">person_add</span>
        </div>
        <p class="text-gray-100 mb-6 font-medium">Create a new user account</p>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-2 px-4 rounded-lg font-medium transition duration-200">
            <span class="material-icons text-lg">add</span>
            Create User
        </a>
    </div>

    <!-- Stats Card -->
    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-lg p-8 text-white transform hover:scale-105 transition duration-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Platform Stats</h3>
            <span class="material-icons text-4xl opacity-30">analytics</span>
        </div>
        <p class="text-4xl font-bold mb-6">Dashboard</p>
        <a href="#" class="inline-flex items-center gap-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-2 px-4 rounded-lg font-medium transition duration-200">
            <span class="material-icons text-lg">arrow_forward</span>
            View Stats
        </a>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('admin.users.create') }}" class="p-4 border-2 border-gray-200 hover:border-blue-500 hover:bg-blue-50 rounded-xl transition duration-200 group">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-100 group-hover:bg-blue-200 rounded-lg transition">
                    <span class="material-icons text-blue-600">person_add</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Create New User</p>
                    <p class="text-sm text-gray-600">Add a new user to the system</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="p-4 border-2 border-gray-200 hover:border-green-500 hover:bg-green-50 rounded-xl transition duration-200 group">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-100 group-hover:bg-green-200 rounded-lg transition">
                    <span class="material-icons text-green-600">list</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">View All Users</p>
                    <p class="text-sm text-gray-600">Manage existing users</p>
                </div>
            </div>
        </a>
    </div>
</div>

@endsection
