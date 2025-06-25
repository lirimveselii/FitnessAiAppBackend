<?php

namespace App\Utils;

class AiResponseParser
{
    public static function clean(string $raw): array
    {
        // Step 1: Trim whitespace
        $clean = trim($raw);

        // Step 2: Remove wrapping quotes, backticks, or markdown
        $clean = str_replace(['```json', '```', '"""'], '', $clean);
        $clean = trim($clean);

        // Step 3: Decode JSON
        $decoded = json_decode($clean, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to parse AI response: ' . json_last_error_msg());
        }

        return $decoded;
    }
}
