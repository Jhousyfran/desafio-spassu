<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $this->formatName($value);
    }

    static public function formatName(string|null $value): string
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
