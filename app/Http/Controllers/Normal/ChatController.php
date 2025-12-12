<?php

namespace App\Http\Controllers\Normal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Template;
use App\Services\AIService;

class ChatController extends Controller
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

        return view('normal.chat', compact('chats', 'messages'));
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
        $brandName = $payload['brand_name'] ?? '';
        $keywords = $payload['keywords'] ?? '';
        $location = $payload['location'] ?? '';

        // Construct the prompt
        $fullPrompt = "You are an expert SEO Content Writer. Your goal is to generate high-quality, SEO-optimized content based on the user's requirements.\n\n";
        $fullPrompt .= "Strict Instructions:\n";
        $fullPrompt .= "1. Ensure the content is fully SEO-friendly, using proper heading hierarchy (H1, H2, H3).\n";
        $fullPrompt .= "2. Incorporate the provided keywords naturally.\n";
        $fullPrompt .= "3. Generate content for ALL sections and requirements provided below. Do not skip any part.\n";
        $fullPrompt .= "4. Maintain the specified brand voice and tone.\n";
        $fullPrompt .= "5. Format the output cleanly using HTML tags (e.g., <h1>, <h2>, <p>, <ul>, <li>) for direct use.\n\n";
        
        $fullPrompt .= "Context:\n";
        if (!empty($brandName)) $fullPrompt .= "- Brand Name: $brandName\n";
        if (!empty($keywords)) $fullPrompt .= "- Keywords: $keywords\n";
        if (!empty($location)) $fullPrompt .= "- Target Location: $location\n";
        $fullPrompt .= "\n";

        $fullPrompt .= "User Requirements:\n";
        if (!empty($structure)) {
            foreach ($structure as $item) {
                // Handle both old format (nested) and new flat format from modal
                $tag = $item['tag'] ?? $item['heading'] ?? '';
                $content = $item['content'] ?? $item['content_note'] ?? '';
                
                if (!empty($tag)) {
                    $fullPrompt .= "- " . $tag;
                    if (!empty($content)) {
                        $fullPrompt .= ": " . $content;
                    }
                    $fullPrompt .= "\n";
                }
            }
        }

        // Title logic
        $title = $brandName ?: 'Untitled Document';
        if ($title === 'Untitled Document' && !empty($structure)) {
             // Fallback to first heading if brand name is empty
             $firstItem = $structure[0] ?? null;
             if ($firstItem) {
                 $title = $firstItem['tag'] ?? $firstItem['heading'] ?? 'Untitled Document';
             }
        }

        // Generate content
        $result = $this->aiService->generateContent($model, $fullPrompt);
        
        // Handle result being either string (error) or array (success)
        if (is_array($result)) {
            $generatedContent = $result['content'];
            $inputTokens = $result['input_tokens'] ?? 0;
            $outputTokens = $result['output_tokens'] ?? 0;
            $cost = $result['cost'] ?? 0;
        } else {
            $generatedContent = $result;
            $inputTokens = 0;
            $outputTokens = 0;
            $cost = 0;
        }

        // Save to database
        $chat = Chat::create([
            'user_id' => Auth::id(),
            'prompt' => $fullPrompt,
            'title' => $title,
            'model' => $model,
            'input_tokens' => $inputTokens,
            'output_tokens' => $outputTokens,
            'cost' => $cost,
        ]);

        // Save the user's prompt as a message
        Message::create([
            'user_id' => Auth::id(),
            'chat_id' => $chat->id,
            'sender' => 'user',
            'content' => $fullPrompt,
        ]);

        // Save the generated content as a message from AI
        Message::create([
            'user_id' => Auth::id(),
            'chat_id' => $chat->id,
            'sender' => 'ai',
            'content' => $generatedContent,
        ]);

        if ($request->wantsJson() || $request->input('ajax')) {
            return response()->json([
                'chat_id' => $chat->id,
                'content' => $generatedContent,
                'redirect_url' => route('normal.document.show', ['chat' => $chat->id, 'model' => $model])
            ]);
        }

        return redirect()->route('normal.document.show', ['chat' => $chat->id, 'model' => $model]);
    }

    public function settings()
    {
        $user = Auth::guard('normaluser')->user();

        // Get statistics
        $documentsCount = Chat::where('user_id', $user->id)->count();
        $templatesCount = Template::where('user_id', $user->id)->count();
        
        // Get API usage (count of messages sent to AI)
        $totalApiCalls = Message::where('user_id', $user->id)
                                ->where('sender', 'ai')
                                ->count();
        
        // Get usage this month
        $apiCallsThisMonth = Message::where('user_id', $user->id)
                                    ->where('sender', 'ai')
                                    ->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
        
        // Get recent activity (last 7 days)
        $recentDocuments = Chat::where('user_id', $user->id)
                               ->where('created_at', '>=', now()->subDays(7))
                               ->count();

        // Calculate Total Cost
        $totalCost = Chat::where('user_id', $user->id)->sum('cost');

        // Calculate Usage by Model
        $modelStats = Chat::where('user_id', $user->id)
                          ->selectRaw('model, count(*) as count, sum(input_tokens) as input_tokens, sum(output_tokens) as output_tokens, sum(cost) as cost')
                          ->groupBy('model')
                          ->get();

        return view('normal.settings', compact(
            'documentsCount',
            'templatesCount',
            'totalApiCalls',
            'apiCallsThisMonth',
            'recentDocuments',
            'totalCost',
            'modelStats'
        ));
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
