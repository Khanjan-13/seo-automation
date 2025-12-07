@extends('layouts.admin')

@section('title', 'User History')

@section('content')

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Document History</h1>
            <p class="text-gray-600 dark:text-gray-400">Documents created by <strong>{{ $user->name }}</strong></p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-lg font-medium transition duration-200 flex items-center gap-2">
            <span class="material-icons text-sm">arrow_back</span>
            Back to Users
        </a>
    </div>

    @if($chats->count() > 0)
        <div class="bg-white dark:bg-[#303030] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Document</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date Created</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($chats as $chat)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0 text-blue-600 dark:text-blue-400 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 transition-colors">
                                            <span class="material-icons text-xl">description</span>
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-xs md:max-w-md" title="{{ $chat->prompt }}">
                                                {{ Str::limit($chat->prompt, 60) }}
                                            </h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                Generated content
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">
                                        {{ $chat->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $chat->created_at->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.users.document', ['user' => $user->id, 'chat' => $chat->id]) }}" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors" title="View Document">
                                            <span class="material-icons text-xl">visibility</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($chats->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $chats->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-16 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-icons text-gray-400 dark:text-gray-500 text-3xl">folder_open</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No documents found</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">This user hasn't generated any content yet.</p>
        </div>
    @endif

@endsection
