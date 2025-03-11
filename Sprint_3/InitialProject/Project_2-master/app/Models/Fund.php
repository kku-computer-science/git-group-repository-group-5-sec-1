<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;
    protected $fillable = [  
        'fund_name',
        'fund_cate',
        'support_resource',
        'user_id',
        'fund_year',
        'fund_details',
        'fund_level',
        'support_resource',
        'fund_agency',
        'created_at',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(FundsCategory::class, 'fund_cate', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fundType()
    {
        return $this->hasOneThrough(
            FundsType::class,
            FundsCategory::class,
            'id',          // Foreign key on funds_category table
            'id',          // Foreign key on funds_type table
            'fund_cate',   // Local key on funds table
            'fund_type_id' // Local key on funds_category table
        );
    }

    public function researchProject()
    {
        return $this->hasMany(ResearchProject::class);
    }


    
}

    
