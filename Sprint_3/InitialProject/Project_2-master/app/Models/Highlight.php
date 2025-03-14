<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    use HasFactory;

    protected $fillable = ['banner', 'topic', 'detail', 'selected'];

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'highlight_tags', 'highlight_id', 'tag_id')
            ->withTimestamps();
    }
}
