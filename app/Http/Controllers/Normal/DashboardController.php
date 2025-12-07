<?php

namespace App\Http\Controllers\Normal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\Message;
use App\Services\AIService;

class DashboardController extends Controller
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

        // Chat history list in sidebar
        $chats = Chat::where('user_id', $user->id)
                     ->latest()
                     ->get();

        // Messages for the main chat window
        $messages = Message::where('user_id', $user->id)
                           ->get();

        return view('normal.dashboard', compact('chats', 'messages'));
    }


    public function prompt(Request $request)
    {
        $user = Auth::guard('normaluser')->user();

        $request->validate([
            'prompt_payload' => 'required',
        ]);

        $payload = json_decode($request->input('prompt_payload'), true);
        $model = $payload['model'] ?? 'chatgpt';
        $structure = $payload['structure'] ?? [];

        // Construct the prompt
        $fullPrompt = "You are an expert SEO Content Writer. Your goal is to generate high-quality, SEO-optimized content based on the user's requirements.\n\n";
        $fullPrompt .= "Strict Instructions:\n";
        $fullPrompt .= "1. Ensure the content is fully SEO-friendly, using proper heading hierarchy (H1, H2, H3).\n";
        $fullPrompt .= "2. Incorporate the provided keywords naturally.\n";
        $fullPrompt .= "3. Generate content for ALL sections and requirements provided below. Do not skip any part.\n";
        $fullPrompt .= "4. Maintain the specified brand voice and tone.\n";
        $fullPrompt .= "5. Format the output cleanly using HTML tags (e.g., <h1>, <h2>, <p>, <ul>, <li>) for direct use.\n\n";
        
        $fullPrompt .= "User Requirements:\n";
        if (!empty($structure)) {
            foreach ($structure as $item) {
                $fullPrompt .= $this->formatPromptItem($item);
            }
        }

        // Call AI Service
        $generatedContent = $this->aiService->generateContent($model, $fullPrompt);

        // Save to database (optional, but good for history)
        $chat = Chat::create([
            'user_id' => $user->id,
            'prompt' => $fullPrompt,
        ]);

        Message::create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'sender' => 'user',
            'content' => $fullPrompt,
        ]);

        Message::create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'sender' => 'ai',
            'content' => $generatedContent,
        ]);

        return redirect()->route('normal.document.show', ['chat' => $chat->id, 'model' => $model]);
    }
    private function formatPromptItem($item, $level = 0)
    {
        $indent = str_repeat("  ", $level);
        $output = $indent . "- " . $item['tag'];
        
        if (!empty($item['content'])) {
            $output .= ": " . $item['content'];
        }
        $output .= "\n";

        if (!empty($item['children'])) {
            foreach ($item['children'] as $child) {
                $output .= $this->formatPromptItem($child, $level + 1);
            }
        }
        return $output;
    }
}
