<?php
return [
    'driver' => env('OCR_DRIVER', 'gemini'),
    'drivers' => [
        'gemini' => [
            'key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-3.8-flash'),
        ],
        'claude' => [
            'key' => env('ANTHROPIC_API_KEY'),
            'model' => env('CLAUDE_OCR_MODEL', 'claude-haiku-5-5'),
        ],
    ],
];
