@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

<div class="mb-8">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium mb-6">
        <span class="material-icons">arrow_back</span>
        Back to Users
    </a>
    <h2 class="text-3xl font-bold text-gray-800">Edit User</h2>
    <p class="text-gray-600 mt-2">Update user information</p>
</div>

@if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg mb-6">
        <p class="font-semibold mb-3">Please fix the following errors:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-lg p-8 max-w-2xl">
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-semibold mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                   class="border p-3 w-full rounded @error('name') border-red-500 @enderror" required>
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-semibold mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                   class="border p-3 w-full rounded @error('email') border-red-500 @enderror" required>
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-semibold mb-2">Mobile</label>
            <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" 
                   class="border p-3 w-full rounded @error('mobile') border-red-500 @enderror" required>
            @error('mobile')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded hover:bg-blue-700">
                Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white py-2 px-6 rounded hover:bg-gray-600">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
