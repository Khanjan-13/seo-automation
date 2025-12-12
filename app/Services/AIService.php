<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function generateContent($model, $prompt)
    {
        $inputTokens = 0;
        $outputTokens = 0;
        $cost = 0;
        $content = '';

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

                    if (!$response->successful()) {
                        return ['content' => $this->handleApiError($response), 'input_tokens' => 0, 'output_tokens' => 0, 'cost' => 0];
                    }

                    $data = $response->json();
                    $content = $data['choices'][0]['message']['content'] ?? 'Error: No response from ChatGPT.';

                    if (isset($data['usage'])) {
                        $inputTokens = $data['usage']['prompt_tokens'];
                        $outputTokens = $data['usage']['completion_tokens'];
                        // Pricing: Input $1.25/M, Output $10.00/M
                        $cost = ($inputTokens / 1_000_000 * 1.25) + ($outputTokens / 1_000_000 * 10.00);
                    }
                    break;

                case 'claude':
                    $response = Http::withHeaders([
                        'x-api-key' => env('CLAUDE_API_KEY'),
                        'anthropic-version' => '2023-06-01',
                        'Content-Type' => 'application/json',
                    ])->post('https://api.anthropic.com/v1/messages', [
                                'model' => 'claude-opus-4-1-20250805',
                                'max_tokens' => 4000,
                                'messages' => [
                                    ['role' => 'user', 'content' => $prompt],
                                ],
                            ]);

                    if (!$response->successful()) {
                        return ['content' => $this->handleApiError($response), 'input_tokens' => 0, 'output_tokens' => 0, 'cost' => 0];
                    }

                    $data = $response->json();
                    $content = $data['content'][0]['text'] ?? 'Error: No response from Claude.';

                    if (isset($data['usage'])) {
                        $inputTokens = $data['usage']['input_tokens'];
                        $outputTokens = $data['usage']['output_tokens'];
                        // Pricing: Input $5.00/M, Output $25.00/M
                        $cost = ($inputTokens / 1_000_000 * 5.00) + ($outputTokens / 1_000_000 * 25.00);
                    }
                    break;

                case 'gemini':
                    $apiKey = env('GEMINI_API_KEY');
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key={$apiKey}", [
                                'contents' => [
                                    ['parts' => [['text' => $prompt]]]
                                ],
                            ]);

                    if (!$response->successful()) {
                        return ['content' => $this->handleApiError($response), 'input_tokens' => 0, 'output_tokens' => 0, 'cost' => 0];
                    }

                    $data = $response->json();
                    $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Error: No response from Gemini.';

                    if (isset($data['usageMetadata'])) {
                        $inputTokens = $data['usageMetadata']['promptTokenCount'];
                        $outputTokens = $data['usageMetadata']['candidatesTokenCount'];
                        // Pricing: Input $2.00/M, Output $12.00/M
                        $cost = ($inputTokens / 1_000_000 * 2.00) + ($outputTokens / 1_000_000 * 12.00);
                    }
                    break;

                case 'perplexity':
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . env('PERPLEXITY_API_KEY'),
                        'Content-Type' => 'application/json',
                    ])->post('https://api.perplexity.ai/chat/completions', [
                                'model' => 'sonar-pro',
                                'messages' => [
                                    ['role' => 'user', 'content' => $prompt],
                                ],
                            ]);

                    if (!$response->successful()) {
                        return ['content' => $this->handleApiError($response), 'input_tokens' => 0, 'output_tokens' => 0, 'cost' => 0];
                    }

                    $data = $response->json();
                    $content = $data['choices'][0]['message']['content'] ?? 'Error: No response from Perplexity.';

                    if (isset($data['usage'])) {
                        $inputTokens = $data['usage']['prompt_tokens'];
                        $outputTokens = $data['usage']['completion_tokens'];
                        // Pricing: Input $3.00/M, Output $15.00/M
                        $cost = ($inputTokens / 1_000_000 * 3.00) + ($outputTokens / 1_000_000 * 15.00);
                    }
                    break;

                default:
                    return [
                        'content' => "Error: Unknown model selected.",
                        'input_tokens' => 0,
                        'output_tokens' => 0,
                        'cost' => 0
                    ];
            }

            return [
                'content' => $this->cleanContent($content),
                'input_tokens' => $inputTokens,
                'output_tokens' => $outputTokens,
                'cost' => $cost
            ];

        } catch (\Exception $e) {
            return [
                'content' => "Error generating content: " . $e->getMessage(),
                'input_tokens' => 0,
                'output_tokens' => 0,
                'cost' => 0
            ];
        }
    }

    private function cleanContent($content)
    {
        // 1. Remove Markdown Code Blocks (e.g., ```html ... ```)
        // This regex looks for ``` followed by optional lang, collects content, and ends with ```
        // It replaces the whole block with just the inner content.
        $content = preg_replace('/^```(?:html)?\s*(.*?)\s*```$/s', '$1', $content);

        // 2. Remove Perplexity Citations (e.g., [1], [2], [10])
        $content = preg_replace('/\[\d+\]/', '', $content);

        return trim($content);
    }

    private function handleApiError($response)
    {
        return "API Error: " . $response->status() . " - " . $response->body();
    }
}
