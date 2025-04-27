<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;
    use ModelTrait;
    use HasFactory;


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
        $this->attributes['name'] = $this->formatField($value);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'topic_book');
    }

}
