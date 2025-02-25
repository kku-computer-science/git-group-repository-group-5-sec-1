<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'highlight_id'];

    public function highlight()
    {
        return $this->belongsTo(Highlight::class);
    }
}
