<?php

namespace App\Utils;

class AiResponseParser
{
    public static function clean(string $raw): array
    {
        // Step 1: Trim whitespace and strip common markdown fencing
        $clean = trim($raw);
        $clean = str_replace(['```json', '```', '"""'], '', $clean);
        $clean = trim($clean);

        // Step 2: Try decoding as-is
        $decoded = json_decode($clean, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // Step 3: Fallback — attempt to extract the first JSON object/array block
        $firstBracePos = strpos($clean, '{');
        $firstBracketPos = strpos($clean, '[');

        $startPosCandidates = array_filter([
            $firstBracePos === false ? null : $firstBracePos,
            $firstBracketPos === false ? null : $firstBracketPos,
        ], function ($v) {
            return $v !== null; });

        if (!empty($startPosCandidates)) {
            $startPos = min($startPosCandidates);
            $endPos = strrpos($clean, '}');
            $endPosBracket = strrpos($clean, ']');
            $endPosCandidates = array_filter([
                $endPos === false ? null : $endPos,
                $endPosBracket === false ? null : $endPosBracket,
            ], function ($v) {
                return $v !== null; });

            if (!empty($endPosCandidates)) {
                $endPosFinal = max($endPosCandidates);
                $maybeJson = substr($clean, $startPos, $endPosFinal - $startPos + 1);
                $decoded = json_decode($maybeJson, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
            }
        }

        throw new \RuntimeException('Failed to parse AI response: ' . json_last_error_msg());
    }
}
