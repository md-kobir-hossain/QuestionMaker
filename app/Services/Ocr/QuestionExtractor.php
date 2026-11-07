<?php

namespace App\Services\Ocr;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class QuestionExtractor
{
    /**
     * Extract a question from an image using the configured OCR driver.
     */
    public function extract(
        string $absolutePath,
        string $mime,
        string $type
    ): array {
        if (!in_array($type, ['mcq', 'short', 'cq'], true)) {
            throw new RuntimeException(
                "Invalid question type: {$type}"
            );
        }

        if (!is_file($absolutePath) || !is_readable($absolutePath)) {
            throw new RuntimeException(
                'Question image file paoa jayni ba read kora jacche na.'
            );
        }

        $driver = config('ocr.driver');
        $cfg = config("ocr.drivers.{$driver}");

        if (!$cfg || empty($cfg['key'])) {
            throw new RuntimeException(
                "OCR driver '{$driver}' er API key set kora nei. .env check korun."
            );
        }

        $imageContent = file_get_contents($absolutePath);

        if ($imageContent === false) {
            throw new RuntimeException(
                'Question image read kora jayni.'
            );
        }

        $base64 = base64_encode($imageContent);
        $prompt = $this->prompt($type);

if ($driver === 'gemini') {
    $text = $this->gemini(
        $cfg,
        $prompt,
        $mime,
        $base64
    );
} elseif ($driver === 'claude') {
    $text = $this->claude(
        $cfg,
        $prompt,
        $mime,
        $base64
    );
} else {
    throw new RuntimeException(
        "Unknown OCR driver: {$driver}"
    );
}
        $data = $this->decodeJson($text);

        return array_merge(
            [
                'question' => '',
                'options' => [
                    'a' => '',
                    'b' => '',
                    'c' => '',
                    'd' => '',
                ],
                'correct_answer' => null,
                'stem' => '',
                'parts' => [],
            ],
            $data,
            ['type' => $type]
        );
    }

    /**
     * Send a request to Gemini with retries for temporary errors.
     */
    private function gemini(
        array $cfg,
        string $prompt,
        string $mime,
        string $base64
    ): string {
        $model = $cfg['model'];

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            try {
                $response = Http::timeout(90)
                    ->withHeaders([
                        'x-goog-api-key' => $cfg['key'],
                    ])
                    ->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => $prompt,
                                    ],
                                    [
                                        'inline_data' => [
                                            'mime_type' => $mime,
                                            'data' => $base64,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'generationConfig' => [
                            'responseMimeType' => 'application/json',
                            'temperature' => 0,
                        ],
                    ]);
            } catch (ConnectionException $e) {
                if ($attempt === 3) {
                    throw new RuntimeException(
                        'Gemini connection failed after 3 attempts.',
                        0,
                        $e
                    );
                }

                sleep($attempt);
                continue;
            }

            if (!$response->failed()) {
                $text = $response->json(
                    'candidates.0.content.parts.0.text'
                );

                if (is_string($text) && trim($text) !== '') {
                    return $text;
                }

                $finishReason = $response->json(
                    'candidates.0.finishReason'
                );

                throw new RuntimeException(
                    'Gemini theke question text paoa jayni.'
                    . ($finishReason ? " Finish reason: {$finishReason}" : '')
                );
            }

            $status = $response->status();
            $message = $response->json('error.message') ?? 'Unknown API error';

            $temporary = in_array(
                $status,
                [429, 500, 502, 503, 504],
                true
            );

            if (!$temporary || $attempt === 3) {
                throw new RuntimeException(
                    "Gemini error ({$status}): {$message}"
                );
            }

            // Retry after 1 second, then 2 seconds.
            sleep($attempt);
        }

        throw new RuntimeException(
            'Gemini request failed after multiple attempts.'
        );
    }

    /**
     * Send a request to Claude as a fallback or primary driver.
     */
    private function claude(
        array $cfg,
        string $prompt,
        string $mime,
        string $base64
    ): string {
        $response = Http::timeout(90)
            ->withHeaders([
                'x-api-key' => $cfg['key'],
                'anthropic-version' => '2023-06-01',
            ])
            ->post('https://api.anthropic.com/v1/messages', [
                'model' => $cfg['model'],
                'max_tokens' => 4096,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'image',
                                'source' => [
                                    'type' => 'base64',
                                    'media_type' => $mime,
                                    'data' => $base64,
                                ],
                            ],
                            [
                                'type' => 'text',
                                'text' => $prompt,
                            ],
                        ],
                    ],
                ],
            ]);

        if ($response->failed()) {
            $status = $response->status();
            $message = $response->json('error.message')
                ?? 'Unknown API error';

            throw new RuntimeException(
                "Claude error ({$status}): {$message}"
            );
        }

        $text = $response->json('content.0.text');

        if (!is_string($text) || trim($text) === '') {
            throw new RuntimeException(
                'Claude theke question text paoa jayni.'
            );
        }

        return $text;
    }

    /**
     * Check whether a Gemini error may be temporary.
     */
    private function isTemporaryError(string $message): bool
    {
        return (bool) preg_match(
            '/\b(429|500|502|503|504)\b'
            . '|high demand'
            . '|overloaded'
            . '|temporarily unavailable'
            . '|connection failed'
            . '|timed out'
            . '|timeout/i',
            $message
        );
    }

    /**
     * Decode JSON returned by the OCR model.
     */
    private function decodeJson(string $text): array
    {
        $text = trim($text);

        // Remove Markdown JSON fences, if present.
        $text = preg_replace(
            '/^\s*```(?:json)?\s*/i',
            '',
            $text
        );

        $text = preg_replace(
            '/\s*```\s*$/',
            '',
            $text
        );

        $data = json_decode(trim($text), true);

        if (!is_array($data)) {
            throw new RuntimeException(
                'OCR theke valid JSON ashe nai. Abar chesta korun.'
            );
        }

        return $data;
    }

    /**
     * Build the extraction prompt for each question type.
     */
    private function prompt(string $type): string
    {
        $base = <<<'PROMPT'
Read the exam question in this image exactly as written.
The language may be Bangla or English.

Rules:
- Preserve the original wording and language.
- Do not translate, summarize, or correct spelling.
- Preserve mathematical expressions using readable Unicode or plain text.
- Do not invent missing or unreadable content.
- Return only valid JSON, without Markdown or extra explanation.
PROMPT;

        $schema = match ($type) {
            'mcq' => <<<'JSON'
{
  "question": "The question text",
  "options": {
    "a": "Option A text",
    "b": "Option B text",
    "c": "Option C text",
    "d": "Option D text"
  },
  "correct_answer": null
}
JSON,

            'short' => <<<'JSON'
{
  "question": "The short question text"
}
JSON,

            'cq' => <<<'JSON'
{
  "stem": "The creative question passage or scenario",
  "parts": [
    {
      "label": "ক",
      "marks": null,
      "text": "The sub-question text"
    }
  ]
}
JSON,

            default => throw new RuntimeException(
                "Unsupported question type: {$type}"
            ),
        };

        return $base
            . "\n\nExpected JSON structure:\n"
            . $schema
            . "\n\nFor MCQ, use correct_answer only when the correct option is clearly shown; otherwise use null."
            . "\nFor CQ, preserve each sub-question and its marks if visible.";
    }
}
