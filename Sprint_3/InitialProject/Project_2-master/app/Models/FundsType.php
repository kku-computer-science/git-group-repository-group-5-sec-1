<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundsType extends Model
{
    protected $table = 'funds_type';
    
    protected $fillable = [
        'name'
    ];

    public function categories()
    {
        return $this->hasMany(FundsCategory::class, 'fund_type_id');
    }
}

