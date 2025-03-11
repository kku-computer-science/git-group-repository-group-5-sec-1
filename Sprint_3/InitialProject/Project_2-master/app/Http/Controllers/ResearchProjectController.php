<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\FundCategory;
use App\Models\FundType;
use App\Models\ResearchProject;
use App\Models\ResponsibleDepartment;
use App\Models\ResponsibleDepartmentResearchProject;
use App\Models\User;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;
use Illuminate\Support\Facades\Log;
use App\Models\Fund;
use App\Models\Outsider;

class ResearchProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = auth()->user()->id;
        if (auth()->user()->HasRole("admin")) {
            $researchProjects = ResearchProject::with([
                "user",
                "fund.category.fundType",
            ])->get();
        } elseif (auth()->user()->HasRole("administrativestaff")) {
            $researchProjects = ResearchProject::with([
                "user",
                "fund.category.fundType",
            ])->get();
        } elseif (auth()->user()->HasRole("headproject")) {
            $researchProjects = ResearchProject::with([
                "user",
                "fund.category.fundType",
            ])->get();
        } elseif (auth()->user()->HasRole("staff")) {
            $researchProjects = ResearchProject::with([
                "user",
                "fund.category.fundType",
            ])->get();
        } else {
            $researchProjects = User::find($id)
                ->researchProject()
                ->with("fund.category.fundType")
                ->get();
            //$researchProjects=User::find($id)->researchProject()->latest()->paginate(5);

            //$researchProjects = ResearchProject::with('User')->latest()->paginate(5);
        }
        //dd($id);
        //$researchProjects = ResearchProject::latest()->paginate(5);
        //$researchProjects = ResearchProject::with('User')->latest()->paginate(5);
        //return $researchProjects;

        return view("research_projects.index", compact("researchProjects"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(["teacher", "student"])->get();
        $fundType = FundType::get();
        $deps = ResponsibleDepartment::get();
        return view(
            "research_projects.create",
            compact("users", "fundType", "deps")
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                "project_name" => "required",
                "project_start" => "required|date",
                "project_end" => "required|date|after_or_equal:project_start",
                "fund" => "required",
                "project_year" => "required|numeric",
                "budget" => "required|numeric",
                "responsible_department" => "required",
                "status" => "required",
                "head" => "required",
            ],
            [
                "project_name.required" => "ต้องใส่ข้อมูล ชื่อโครงการวิจัย",
                "project_start.required" => "ต้องใส่ข้อมูล วันที่เริ่มโครงการ",
                "project_end.required" => "ต้องใส่ข้อมูล วันที่สิ้นสุดโครงการ",
                "budget.required" => "ต้องใส่ข้อมูล งบประมาณ",
                "project_year.required" => "ต้องใส่ข้อมูล ปีที่ปีที่ยื่นขอ",
                "fund.required" => "ต้องใส่ข้อมูล ทุนวิจัย",
                "head.required" => "ต้องใส่ข้อมูล ผู้รับผิดชอบโครงการ",
                "responsible_department.required" =>
                    "ต้องใส่ข้อมูล หน่วยงานที่รับผิดชอบ",
                "status.required" => "ต้องใส่ข้อมูล สถานะโครงการ",
            ]
        );

        // Create research project
        $researchProject = ResearchProject::create([
            "project_name" => $request->project_name,
            "project_start" => $request->project_start,
            "project_end" => $request->project_end,
            "budget" => $request->budget,
            "show_budget" => $request->show_budget,
            "note" => $request->note,
            "status" => $request->status,
            "project_year" => $request->project_year,
            "fund_id" => $request->fund,
        ]);

        // Store responsible department
        ResponsibleDepartmentResearchProject::create([
            "research_projects_id" => $researchProject->id,
            "responsible_department_id" => $request->responsible_department,
        ]);

        //return $request->fund;
        $fund = Fund::find($request->fund);
        //$req = $request->all();
        //return $req;
        //$req['project_year'] = $req['project_year'] - 543;

        //$researchProject = $fund->researchProject()->Create($req);
        //$researchProject = $fund->researchProject()->save($fund);
        //$fund = $request->fund;
        //$researchProject = ResearchProject::create($request->all());

        //$researchProject->fund()->create($fund);

        $head = $request->head;
        $researchProject->user()->attach($head, ["role" => 1]);
        //$user=auth()->user();
        //$user=User::find($head);
        //$user->givePermissionTo('editResearchProject','deleteResearchProject');

        if (isset($request->moreFields)) {
            foreach ($request->moreFields as $key => $value) {
                //dd($value);
                if ($value["userid"] != null) {
                    $researchProject->user()->attach($value, ["role" => 2]);
                }
                //$user->givePermissionTo('readResearchProject');
            }
        }
        $input = $request->except(["_token"]);
        //$x = 1;
        //return isset($input['fname']);
        //$length = count($request->input('fname'));
        if (isset($input["fname"][0]) and !empty($input["fname"][0])) {
            foreach ($request->input("fname") as $key => $value) {
                $data["fname"] = $input["fname"][$key];
                $data["lname"] = $input["lname"][$key];
                $data["title_name"] = $input["title_name"][$key];

                if (
                    Outsider::where("fname", "=", $data["fname"])
                        ->orWhere("lname", "=", $data["lname"])
                        ->first() == null
                ) {
                    $author = new Outsider();
                    $author->fname = $data["fname"];
                    $author->lname = $data["lname"];
                    $author->title_name = $data["title_name"];
                    $author->save();
                    $researchProject
                        ->outsider()
                        ->attach($author, ["role" => 2]);
                } else {
                    $author = Outsider::where("fname", "=", $data["fname"])
                        ->orWhere("lname", "=", $data["lname"])
                        ->first();
                    $authorid = $author->id;
                    $researchProject
                        ->outsider()
                        ->attach($authorid, ["role" => 2]);
                }
                //$x++;
            }
        }

        //$user = User::find(auth()->user()->id);
        //$user->researchProject()->attach(2);

        return redirect()
            ->route("researchProjects.index")
            ->with("success", "research projects created successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResearchProject  $researchProject
     * @return \Illuminate\Http\Response
     */
    public function show(ResearchProject $researchProject)
    {
        return view("research_projects.show", compact("researchProject"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResearchProject  $researchProject
     * @return \Illuminate\Http\Response
     */
    public function edit(ResearchProject $researchProject)
    {
        $researchProject = ResearchProject::find($researchProject->id);
        $this->authorize("update", $researchProject);

        // โหลดทุกความสัมพันธ์ที่จำเป็น
        $researchProject = ResearchProject::with([
            "user",
            "fund.category.fundType",
            "outsider",
            "responsibleDepartmentResearchProject.responsibleDepartment", // เพิ่มนี้
        ])
            ->where("id", $researchProject->id)
            ->first();

        $users = User::role(["teacher", "student"])->get();
        $fundType = FundType::get();

        // ใช้ ResponsibleDepartment แทน Department
        $deps = ResponsibleDepartment::get();

        // Debug เพื่อตรวจสอบข้อมูล
        // dd($researchProject->responsibleDepartmentResearchProject);

        return view(
            "research_projects.edit",
            compact("researchProject", "users", "fundType", "deps")
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResearchProject  $researchProject
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, ResearchProject $researchProject)
     {
         $request->validate(
             [
                 "project_name" => "required",
                 "project_start" => "required|date",
                 "project_end" => "required|date|after_or_equal:project_start",
                 "funds_type_id" => "required",
                 "fund_cate" => "required",
                 "project_year" => "required|numeric",
                 "budget" => "required|numeric",
                 "responsible_department" => "required",
                 "status" => "required",
                 "head" => "required",
             ],
             [
                 "project_name.required" => "ต้องใส่ข้อมูล ชื่อโครงการวิจัย",
                 "project_start.required" => "ต้องใส่ข้อมูล วันที่เริ่มโครงการ",
                 "project_end.required" => "ต้องใส่ข้อมูล วันที่สิ้นสุดโครงการ",
                 "budget.required" => "ต้องใส่ข้อมูล งบประมาณ",
                 "project_year.required" => "ต้องใส่ข้อมูล ปีที่ปีที่ยื่นขอ",
                 "funds_type_id.required" => "ต้องใส่ข้อมูล ประเภททุน",
                 "fund_cate.required" => "ต้องใส่ข้อมูล ลักษณะทุน",
                 "head.required" => "ต้องใส่ข้อมูล ผู้รับผิดชอบโครงการ",
                 "responsible_department.required" => "ต้องใส่ข้อมูล หน่วยงานที่รับผิดชอบ",
                 "status.required" => "ต้องใส่ข้อมูล สถานะโครงการ",
             ]
         );

         // หา fund_id จาก fund_cate
         $fund = Fund::where('fund_cate', $request->fund_cate)->first();

         // ถ้าไม่มี Fund ให้สร้างใหม่
         if (!$fund) {
             // ขอชื่อ category เพื่อตั้งชื่อ fund
             $category = FundCategory::find($request->fund_cate);
             if (!$category) {
                 return redirect()->back()->with('error', 'ไม่พบลักษณะทุนที่เลือก');
             }

             $fund = Fund::create([
                 'fund_name' => $category->name, // ใช้ชื่อจาก category
                 'fund_cate' => $request->fund_cate, // ใช้ fund_cate แทน category_id
             ]);
         }

         $researchProject = ResearchProject::find($researchProject->id);
         $this->authorize("update", $researchProject);

         // อัปเดตข้อมูลโครงการวิจัย
         $researchProject->update([
             "project_name" => $request->project_name,
             "project_start" => $request->project_start,
             "project_end" => $request->project_end,
             "budget" => $request->budget,
             "show_budget" => $request->show_budget ?? 0,
             "note" => $request->note,
             "status" => $request->status,
             "project_year" => $request->project_year,
             "fund_id" => $fund->id,
         ]);

         // อัปเดตหน่วยงานที่รับผิดชอบ
         if ($request->has('responsible_department')) {
             // ลบความสัมพันธ์เดิม
             ResponsibleDepartmentResearchProject::where('research_projects_id', $researchProject->id)->delete();

             // สร้างความสัมพันธ์ใหม่
             ResponsibleDepartmentResearchProject::create([
                 'research_projects_id' => $researchProject->id,
                 'responsible_department_id' => $request->responsible_department
             ]);
         }

         // อัปเดตผู้รับผิดชอบโครงการ (ส่วนที่เหลือไม่เปลี่ยนแปลง)
         $head = $request->head;
         $researchProject->user()->detach();
         $researchProject->user()->attach($head, ["role" => 1]);

         // อัปเดตผู้รับผิดชอบโครงการร่วมภายใน
         if (isset($request->moreFields)) {
             foreach ($request->moreFields as $key => $value) {
                 if ($value["userid"] != null) {
                     $researchProject->user()->attach($value["userid"], ["role" => 2]);
                 }
             }
         }

         // อัปเดตผู้รับผิดชอบโครงการร่วมภายนอก
         $input = $request->except(["_token", "_method"]);
         $researchProject->outsider()->detach();

         if (isset($input["fname"][0]) && !empty($input["fname"][0])) {
             foreach ($request->input("fname") as $key => $value) {
                 $data["fname"] = $input["fname"][$key];
                 $data["lname"] = $input["lname"][$key];
                 $data["title_name"] = $input["title_name"][$key];

                 // หาหรือสร้าง outsider ใหม่
                 $author = Outsider::updateOrCreate(
                     [
                         "fname" => $data["fname"],
                         "lname" => $data["lname"]
                     ],
                     [
                         "title_name" => $data["title_name"]
                     ]
                 );

                 // เพิ่มความสัมพันธ์
                 $researchProject->outsider()->attach($author->id, ["role" => 2]);
             }
         }

         return redirect()
             ->route("researchProjects.index")
             ->with("success", "Research Project updated successfully");
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ResearchProject  $researchProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchProject $researchProject)
    {
        $this->authorize("delete", $researchProject);
        $researchProject->delete();
        return redirect()
            ->route("researchProjects.index")
            ->with("success", "Research Project deleted successfully");
    }
}
