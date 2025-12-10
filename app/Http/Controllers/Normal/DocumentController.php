<?php

namespace App\Http\Controllers\Normal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\Message;
use App\Models\DocumentShare;
use App\Services\AIService;
use Illuminate\Support\Str;

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

        return view('normal.document', compact('generatedContent', 'model', 'chat'));
    }

    public function update(Request $request, $chatId)
    {
        $user = Auth::guard('normaluser')->user();
        $chat = Chat::where('user_id', $user->id)->findOrFail($chatId);

        $request->validate([
            'content' => 'nullable',
            'title' => 'nullable|string|max:255',
        ]);

        // Update title if provided
        if ($request->has('title')) {
            $chat->update(['title' => $request->title]);
            return response()->json(['success' => true, 'message' => 'Title updated successfully.']);
        }

        // Update content if provided
        if ($request->has('content')) {
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

        return response()->json(['success' => false, 'message' => 'No data to update.'], 400);
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

        $result = $this->aiService->generateContent($model, $prompt);
        
        // Handle result being either string (error) or array (success)
        if (is_array($result)) {
            $newContent = $result['content'];
            // Note: We are currently not tracking cost for regeneration as this endpoint is stateless/doesn't receive chat_id
        } else {
            $newContent = $result;
        }

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

    public function generateShare(Request $request, $chatId)
    {
        $user = Auth::guard('normaluser')->user();
        $chat = Chat::where('user_id', $user->id)->findOrFail($chatId);

        // Check if share link already exists
        $share = DocumentShare::where('chat_id', $chat->id)
                              ->where('is_active', true)
                              ->first();

        if (!$share) {
            // Generate new share token
            $share = DocumentShare::create([
                'chat_id' => $chat->id,
                'share_token' => Str::random(32),
                'is_active' => true,
            ]);
        }

        $shareUrl = route('share.view', ['token' => $share->share_token]);
        $googleDocsUrl = 'https://docs.google.com/document/create?url=' . urlencode($shareUrl);

        return response()->json([
            'success' => true,
            'share_url' => $shareUrl,
            'google_docs_url' => $googleDocsUrl,
        ]);
    }
}
