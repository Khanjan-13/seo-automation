@extends('layouts.normal')

@section('title', 'Settings')

@section('chat')

<div class="w-full max-w-6xl mx-auto py-8 px-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Settings & Profile</h1>
        <p class="text-gray-600 dark:text-gray-400">Manage your account, view statistics, and track your usage</p>
    </div>

    <!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

  <!-- Card -->
  <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
              bg-white/80 dark:bg-zinc-900/80 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="h-10 w-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400
                  flex items-center justify-center">
        <span class="material-icons text-lg">description</span>
      </div>
      <span class="material-icons text-zinc-400">trending_up</span>
    </div>

    <div class="mt-4">
      <h3 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
        {{ $documentsCount }}
      </h3>
      <p class="mt-1 text-sm text-zinc-500">Documents Created</p>
      <p class="mt-2 text-xs text-zinc-400">
        {{ $recentDocuments }} in last 7 days
      </p>
    </div>
  </div>

  <!-- Card -->
  <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
              bg-white/80 dark:bg-zinc-900/80 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="h-10 w-10 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400
                  flex items-center justify-center">
        <span class="material-icons text-lg">folder_special</span>
      </div>
      <span class="material-icons text-zinc-400">star</span>
    </div>

    <div class="mt-4">
      <h3 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
        {{ $templatesCount }}
      </h3>
      <p class="mt-1 text-sm text-zinc-500">Templates Saved</p>
      <p class="mt-2 text-xs text-zinc-400">Custom templates</p>
    </div>
  </div>

  <!-- Card -->
  <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
              bg-white/80 dark:bg-zinc-900/80 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="h-10 w-10 rounded-xl bg-green-500/10 text-green-600 dark:text-green-400
                  flex items-center justify-center">
        <span class="material-icons text-lg">api</span>
      </div>
      <span class="material-icons text-zinc-400">cloud_done</span>
    </div>

    <div class="mt-4">
      <h3 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
        {{ $totalApiCalls }}
      </h3>
      <p class="mt-1 text-sm text-zinc-500">AI Generations</p>
      <p class="mt-2 text-xs text-zinc-400">All time usage</p>
    </div>
  </div>

  <!-- Card -->
  <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
              bg-white/80 dark:bg-zinc-900/80 backdrop-blur p-5">
    <div class="flex items-center justify-between">
      <div class="h-10 w-10 rounded-xl bg-orange-500/10 text-orange-600 dark:text-orange-400
                  flex items-center justify-center">
        <span class="material-icons text-lg">calendar_today</span>
      </div>
      <span class="material-icons text-zinc-400">auto_awesome</span>
    </div>

    <div class="mt-4">
      <h3 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
        {{ $apiCallsThisMonth }}
      </h3>
      <p class="mt-1 text-sm text-zinc-500">This Month</p>
      <p class="mt-2 text-xs text-zinc-400">
        {{ now()->format('F Y') }}
      </p>
    </div>
  </div>

</div>


    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Profile & Account -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information -->
            <div class="bg-white dark:bg-[#303030] border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <span class="material-icons text-white text-2xl">person</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Profile Information</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Your account details</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                            <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <span class="material-icons text-gray-400 text-sm">badge</span>
                                <span class="text-gray-900 dark:text-white">{{ auth('normaluser')->user()->name }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                            <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <span class="material-icons text-gray-400 text-sm">email</span>
                                <span class="text-gray-900 dark:text-white">{{ auth('normaluser')->user()->email }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                        <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <span class="material-icons text-gray-400 text-sm">phone</span>
                            <span class="text-gray-900 dark:text-white">{{ auth('normaluser')->user()->mobile ?? 'Not provided' }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Member Since</label>
                        <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <span class="material-icons text-gray-400 text-sm">event</span>
                            <span class="text-gray-900 dark:text-white">{{ auth('normaluser')->user()->created_at->format('F d, Y') }}</span>
                        </div>
                    </div>

                    
                </div>
            </div>

            
        </div>

        <!-- Right Column - Preferences & Security -->
        <div class="space-y-6">
           <!-- API Usage Details -->
            <div class="bg-white dark:bg-[#303030] border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <span class="material-icons text-white text-2xl">analytics</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">API Usage</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Your AI generation statistics</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Usage Bar -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Monthly Usage</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $apiCallsThisMonth }} / ∞</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-3 rounded-full transition-all duration-500" style="width: {{ min(($apiCallsThisMonth / max($totalApiCalls, 1)) * 100, 100) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Unlimited generations available</p>
                    </div>

                    <!-- Usage Breakdown -->
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalApiCalls }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Total Generations</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $apiCallsThisMonth }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">This Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Cost & Usage Analysis -->
    <div class="mt-8 bg-white dark:bg-[#303030] border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons text-white text-2xl">monetization_on</span>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">AI Cost & Usage Analysis</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Detailed breakdown of your AI model usage</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Estimated Cost</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($totalCost, 4) }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">Model</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-center">Generations</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-right">Input Tokens</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-right">Output Tokens</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 text-right">Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($modelStats as $stat)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-medium text-gray-900 dark:text-white capitalize">
                                    {{ $stat->model ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center text-gray-700 dark:text-gray-300">
                                {{ number_format($stat->count) }}
                            </td>
                            <td class="py-3 px-4 text-right text-gray-700 dark:text-gray-300">
                                {{ number_format($stat->input_tokens) }}
                            </td>
                            <td class="py-3 px-4 text-right text-gray-700 dark:text-gray-300">
                                {{ number_format($stat->output_tokens) }}
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-green-600 dark:text-green-400">
                                ${{ number_format($stat->cost, 4) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                No usage data available yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
