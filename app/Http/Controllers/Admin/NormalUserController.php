<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NormalUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NormalUserController extends Controller
{
    public function index()
    {
        $users = NormalUser::latest()->simplePaginate(6);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:normal_users',
            'mobile' => 'required|unique:normal_users',
            'password' => 'required|min:6',
        ]);

        NormalUser::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function edit(NormalUser $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, NormalUser $user)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:normal_users,email,' . $user->id,
            'mobile' => 'required|unique:normal_users,mobile,' . $user->id,
        ]);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(NormalUser $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    public function history(NormalUser $user)
    {
        $chats = $user->chats()->latest()->simplePaginate(6);
        return view('admin.users.history', compact('user', 'chats'));
    }

    public function viewDocument(NormalUser $user, $chatId)
    {
        $chat = $user->chats()->findOrFail($chatId);
        
        // Find the AI response
        $aiMessage = \App\Models\Message::where('chat_id', $chat->id)
                            ->where('sender', 'ai')
                            ->latest()
                            ->first();

        $generatedContent = $aiMessage ? $aiMessage->content : 'No content found.';
        $model = 'AI Model'; // Default model name
        
        return view('admin.users.view-document', compact('generatedContent', 'model', 'chat', 'user'));
    }
}
