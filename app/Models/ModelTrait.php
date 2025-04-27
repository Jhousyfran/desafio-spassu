<?php

namespace App\Models;

Trait ModelTrait
{
    static public function formatField(string|null $value): string
    {
        if (null === $value || empty($value)) {
            return '';
        }

        $words = explode('-', mb_strtolower($value, 'UTF-8'));

        $words = array_map(function ($word) {
            return mb_convert_case($word, MB_CASE_TITLE, 'UTF-8');
        }, $words);

        return implode('-', $words);
    }
}