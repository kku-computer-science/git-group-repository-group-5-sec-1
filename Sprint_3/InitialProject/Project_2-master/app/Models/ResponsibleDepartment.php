<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsibleDepartment extends Model
{
    use HasFactory;

    protected $table = 'responsible_department';
    public $timestamps = false;
    protected $fillable = ['name', 'type'];



    public function researchProjects()
    {
        return $this->belongsToMany(
            ResearchProject::class,
            'responsible_department_research_projects',
            'responsible_department_id',
            'research_projects_id'
        );
    }
}
