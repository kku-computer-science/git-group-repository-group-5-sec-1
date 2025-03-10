<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundsCategory extends Model
{
    use HasFactory;

    protected $table = 'funds_category';
    protected $fillable = [
        'name',
        'fund_type_id'
    ];

    public function fundType()
    {
        return $this->belongsTo(FundsType::class, 'fund_type_id', 'id');
    }
}

