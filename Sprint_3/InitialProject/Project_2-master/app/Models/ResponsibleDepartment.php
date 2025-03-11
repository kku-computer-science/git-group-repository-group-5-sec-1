<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsibleDepartment extends Model
{
    use HasFactory;

    protected $table = 'responsible_department';

    protected $fillable = ['name', 'type'];
}
