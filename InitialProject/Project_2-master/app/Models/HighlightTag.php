<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighlightTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'highlight_id',
        'tag_id',
    ];
}
