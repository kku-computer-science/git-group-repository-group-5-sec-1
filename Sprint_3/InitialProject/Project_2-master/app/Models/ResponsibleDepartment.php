<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ResponsibleDepartment extends Model
{
    protected $table = 'responsible_department';
    
    protected $fillable = [
        'name',
        'type'
    ];

    public function researchProjects()
    {
        return $this->belongsToMany(ResearchProject::class, 'responsible_department_research_projects');
    }
}