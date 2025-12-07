<?php

namespace App\Http\Controllers\Normal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\Message;
use App\Services\AIService;

class DocumentController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->middleware('auth:normaluser');
        $this->aiService = $aiService;
    }

    public function index()
    {
        $user = Auth::guard('normaluser')->user();
        $chats = Chat::where('user_id', $user->id)->latest()->simplePaginate(6);
        return view('normal.documents', compact('chats'));
    }

    public function show($chatId)
    {
        $user = Auth::guard('normaluser')->user();
        $chat = Chat::where('user_id', $user->id)->findOrFail($chatId);
        
        // Find the AI response
        $aiMessage = Message::where('chat_id', $chat->id)
                            ->where('sender', 'ai')
                            ->latest()
                            ->first();

        $generatedContent = $aiMessage ? $aiMessage->content : 'No content found.';
        $model = request('model', 'AI Model'); // Fallback if model not passed

        return view('normal.document', compact('generatedContent', 'model'));
    }

    public function update(Request $request, $chatId)
    {
        $user = Auth::guard('normaluser')->user();
        $chat = Chat::where('user_id', $user->id)->findOrFail($chatId);

        $request->validate([
            'content' => 'required',
        ]);

        // Update the latest AI message
        $aiMessage = Message::where('chat_id', $chat->id)
                            ->where('sender', 'ai')
                            ->latest()
                            ->first();

        if ($aiMessage) {
            $aiMessage->update(['content' => $request->content]);
            return response()->json(['success' => true, 'message' => 'Document saved successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Document not found.'], 404);
    }

    public function regenerateSection(Request $request)
    {
        $request->validate([
            'section_content' => 'required',
            'context' => 'nullable',
            'model' => 'required',
            'instructions' => 'nullable|string',
        ]);

        $sectionContent = $request->input('section_content');
        $context = $request->input('context');
        $model = $request->input('model');
        $instructions = $request->input('instructions');

        $prompt = "You are an expert editor. Regenerate the following section of content to improve clarity and flow, while maintaining the same tone and style as the preceding context.\n\n";
        
        if ($instructions) {
            $prompt .= "User Instructions (High Priority): " . $instructions . "\n\n";
        }

        if ($context) {
            $prompt .= "Context (Preceding text):\n\"" . substr($context, -500) . "\"\n\n"; // Limit context to last 500 chars
        }
        $prompt .= "Section to Regenerate:\n\"" . $sectionContent . "\"\n\n";
        $prompt .= "Instructions:\n";
        $prompt .= "1. Output ONLY the regenerated content for the section. Do not include the heading if it was part of the input, unless asked.\n";
        $prompt .= "2. Maintain the formatting (HTML tags) if present.\n";
        $prompt .= "3. Do not add any conversational filler.\n";

        $newContent = $this->aiService->generateContent($model, $prompt);

        return response()->json(['success' => true, 'content' => $newContent]);
    }

    public function destroy($chatId)
    {
        $user = Auth::guard('normaluser')->user();
        $chat = Chat::where('user_id', $user->id)->findOrFail($chatId);
        
        // Delete associated messages first (if no cascade delete on DB)
        Message::where('chat_id', $chat->id)->delete();
        
        $chat->delete();

        return redirect()->route('normal.documents')->with('success', 'Document deleted successfully.');
    }
}
