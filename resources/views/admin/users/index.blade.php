@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Manage Users</h2>
        <p class="text-gray-600 mt-2">View, edit, and delete user accounts</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-[#10a37f] hover:bg-[#1a7f64] text-white py-3 px-6 rounded-lg font-medium transition duration-200 ">
        <span class="material-icons">add</span>
        Create User
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-4 rounded-lg mb-6 flex items-center gap-3">
        <span class="material-icons text-green-600">check_circle</span>
        <p>{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-4 rounded-lg mb-6 flex items-center gap-3">
        <span class="material-icons text-red-600">error</span>
        <p>{{ session('error') }}</p>
    </div>
@endif

<div class="overflow-x-auto bg-white rounded-2xl shadow-lg">
    <table class="w-full border-collapse">
        <thead class="bg-gray-200">
            <tr>
                <th class="border p-3 text-left">ID</th>
                <th class="border p-3 text-left">Name</th>
                <th class="border p-3 text-left">Email</th>
                <th class="border p-3 text-left">Mobile</th>
                <th class="border p-3 text-left">Created At</th>
                <th class="border p-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr class="hover:bg-gray-50 border-b">
                    <td class="border p-3">{{ $user->id }}</td>
                    <td class="border p-3 font-semibold">{{ $user->name }}</td>
                    <td class="border p-3">{{ $user->email }}</td>
                    <td class="border p-3">{{ $user->mobile }}</td>
                    <td class="border p-3 text-sm text-gray-600">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="border p-3 text-center space-x-2 flex justify-center">
                        <a href="{{ route('admin.users.history', $user->id) }}" 
                           class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 text-sm">
                            History
                        </a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                           class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600 text-sm">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" 
                              style="display: inline;" 
                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700 text-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border p-6 text-center text-gray-500">
                        No users found. <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:underline">Create one</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6 flex justify-center">
    {{ $users->links() }}
</div>

@endsection
