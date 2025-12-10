@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="mb-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Welcome, Admin! 👋</h1>
    <p class="text-gray-600 text-lg">Manage your platform, users, and settings from this dashboard.</p>
</div>

<!-- Dashboard Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">

  <!-- Users -->
  <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
              bg-white/80 dark:bg-zinc-900/80 backdrop-blur p-6 transition
              hover:border-blue-500/40">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-zinc-500">Total Users</p>
        <h3 class="mt-1 text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
          {{ number_format($totalUsers) }}
        </h3>
      </div>
      <div class="h-11 w-11 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400
                  flex items-center justify-center">
        <span class="material-icons text-xl">people</span>
      </div>
    </div>

    <a href="{{ route('admin.users.index') }}"
       class="mt-5 inline-flex items-center gap-1.5 text-sm font-medium
              text-blue-600 dark:text-blue-400 hover:underline">
      View all users
      <span class="material-icons text-sm">arrow_forward</span>
    </a>
  </div>

  <!-- Documents -->
  <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
              bg-white/80 dark:bg-zinc-900/80 backdrop-blur p-6 transition
              hover:border-green-500/40">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-zinc-500">Documents Created</p>
        <h3 class="mt-1 text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
          {{ number_format($totalDocuments) }}
        </h3>
        <p class="mt-1 text-xs text-zinc-400">
          +{{ $recentDocuments }} in last 7 days
        </p>
      </div>
      <div class="h-11 w-11 rounded-xl bg-green-500/10 text-green-600 dark:text-green-400
                  flex items-center justify-center">
        <span class="material-icons text-xl">description</span>
      </div>
    </div>

    <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-medium
                 text-zinc-600 dark:text-zinc-400 cursor-default">
      Content stats
      <span class="material-icons text-sm">analytics</span>
    </span>
  </div>

  <!-- API Usage -->
  <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
              bg-white/80 dark:bg-zinc-900/80 backdrop-blur p-6 transition
              hover:border-purple-500/40">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-zinc-500">API Usage</p>
        <h3 class="mt-1 text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
          {{ number_format($totalApiCalls) }}
        </h3>
        <p class="mt-1 text-xs text-zinc-400">
          {{ $apiCallsThisMonth }} calls this month
        </p>
      </div>
      <div class="h-11 w-11 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400
                  flex items-center justify-center">
        <span class="material-icons text-xl">api</span>
      </div>
    </div>

    <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-medium
                 text-zinc-600 dark:text-zinc-400 cursor-default">
      System health
      <span class="material-icons text-sm">insights</span>
    </span>
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
