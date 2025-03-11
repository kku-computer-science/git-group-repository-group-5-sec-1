<?php

namespace App\Http\Controllers;
use App\Models\ResearchProject;
use Illuminate\Http\Request;

class ResearchProjController extends Controller
{
    public function index()
    {
        $resp = ResearchProject::with(
            "User",
            "Fund",
            "responsibleDepartmentResearchProject.responsibleDepartment"
        )
            ->orderBy("project_year", "desc")
            ->get();
        return view("research_proj", compact("resp"));
    }
}
