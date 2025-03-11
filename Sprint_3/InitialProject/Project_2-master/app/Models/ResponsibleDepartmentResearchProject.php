<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsibleDepartmentResearchProject extends Model
{
    use HasFactory;
    protected $table = "responsible_department_research_projects";
    public $timestamps = false;

    protected $fillable = ["research_projects_id", "responsible_department_id"];

    public function researchProject()
    {
        return $this->belongsTo(ResearchProject::class, "research_projects_id");
    }

    public function responsibleDepartment()
    {
        return $this->belongsTo(ResponsibleDepartment::class, "responsible_department_id");
    }
}
