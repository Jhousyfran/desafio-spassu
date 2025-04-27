<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use ModelTrait;
    use SoftDeletes;
    use HasFactory;


    protected $fillable = [
        'title',
        'subtitle',
        'publisher',
        'edition',
        'year_of_publication',
        'price',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function setTitleAttribute($value): void
    {
        $this->attributes['title'] = $this->formatField($value);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_book');
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'topic_book');
    }

    public function getRelationsToSync(): array
    {
        return [
            'authors' => 'authors',
            'topics' => 'topics',
        ];
    }

    public function getPriceAttribute($value): string
    {
        return number_format($value, 2, '.', '');
    }
}
