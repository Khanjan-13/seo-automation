<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function generateContent($model, $prompt)
    {
        try {
            switch ($model) {
                case 'chatgpt':
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                        'Content-Type' => 'application/json',
                    ])->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4o',
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt],
                        ],
                    ]);
                    return $response->json()['choices'][0]['message']['content'] ?? 'Error: No response from ChatGPT.';

                case 'claude':
                    $response = Http::withHeaders([
                        'x-api-key' => env('CLAUDE_API_KEY'),
                        'anthropic-version' => '2023-06-01',
                        'Content-Type' => 'application/json',
                    ])->post('https://api.anthropic.com/v1/messages', [
                        'model' => 'claude-opus-4-1-20250805',
                        'max_tokens' => 8000,
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt],
                        ],
                    ]);
                    return $response->json()['content'][0]['text'] ?? 'Error: No response from Claude.';

                case 'gemini':
                    $apiKey = env('GEMINI_API_KEY');
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-pro:generateContent?key={$apiKey}", [
                        'contents' => [
                            ['parts' => [['text' => $prompt]]]
                        ],
                    ]);
                    return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Error: No response from Gemini.';

                case 'perplexity':
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . env('PERPLEXITY_API_KEY'),
                        'Content-Type' => 'application/json',
                    ])->post('https://api.perplexity.ai/chat/completions', [
                        'model' => 'sonar-large-chat',
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt],
                        ],
                    ]);
                    return $response->json()['choices'][0]['message']['content'] ?? 'Error: No response from Perplexity.';

                default:
                    return "Error: Unknown model selected.";
            }
        } catch (\Exception $e) {
            return "Error generating content: " . $e->getMessage();
        }
    }
}
