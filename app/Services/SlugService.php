<?php

namespace App\Services;

use Illuminate\Support\Str;

class SlugService
{
    /**
     * Universal multi-language slug helper (laravel-universal-slug equivalent).
     */
    public function make(string $text, string $fallback = 'item'): string
    {
        $slug = Str::slug($text);
        if ($slug === '') {
            // Keep unicode letters by transliteration fallback
            $slug = preg_replace('/\s+/u', '-', trim($text)) ?: $fallback;
            $slug = preg_replace('/[^\p{L}\p{N}\-_]+/u', '', $slug) ?: $fallback;
        }

        return strtolower($slug);
    }
}