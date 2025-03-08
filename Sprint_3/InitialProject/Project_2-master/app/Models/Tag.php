<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function highlights()
    {
        return $this->belongsToMany(Highlight::class, 'highlight_tags', 'tag_id', 'highlight_id')
            ->withTimestamps();
    }
}
