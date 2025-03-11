<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FundsCategory;
use App\Models\FundsType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Encryption\DecryptException;
class FundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // Pull FundsType data from mysql
    public function getFundsType()
    {
        $types = FundsType::select("id", "name")->get();
        return response()->json($types);
    }

    // Pull FundsCatagory Base on selected FundsType
    public function getFundsCategory($fund_type_id)
    {
        $categories = DB::table("funds_category")
            ->where("fund_type_id", $fund_type_id)
            ->select("id", "name")
            ->get();

        return response()->json($categories);
    }

    // public function index()
    // {
    //     //$funds = Fund::latest()->paginate(5);
    //     $id = auth()->user()->id;
    //     if (auth()->user()->HasRole('admin')) {
    //         $funds = Fund::with('User')->get();
    //     } elseif (auth()->user()->HasRole('headproject')) {
    //         $funds = Fund::with('User')->get();

    //     } elseif (auth()->user()->HasRole('staff')) {
    //         $funds = Fund::with('User')->get();
    //     } else {
    //         $funds = User::find($id)->fund()->get();
    //         //$researchProjects=User::find($id)->researchProject()->latest()->paginate(5);

    //         //$researchProjects = ResearchProject::with('User')->latest()->paginate(5);
    //     }

    //     return view('funds.index', compact('funds'));
    // }

    public function index()
    {
        if (auth()->user()->HasRole("admin")) {
            $funds = Fund::with(["category", "fundType"])
                ->latest()
                ->get();
        } elseif (auth()->user()->HasRole("administrativestaff")) {
            $funds = Fund::with(["category", "fundType"])
                ->latest()
                ->get();
        } elseif (auth()->user()->HasRole("staff")) {
            $funds = Fund::with(["category", "fundType"])
                ->latest()
                ->get();
        } else {
            $funds = User::find(auth()->id())
                ->fund()
                ->with(["category", "fundType"])
                ->latest()
                ->get();
        }

        return view("funds.index", compact("funds"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fundType = DB::table("funds_type")->get();
        $fundCategory = DB::table("funds_category")->get();
        return view("funds.create", compact("fundType", "fundCategory"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // 1. Validate the request
        $request->validate(
            [
                "funds_type_id" => "required",
                "fund_cate" => "required",
                "fund_name" => "required",
                "support_resource" => "required",
            ],
            [
                "funds_type_id.required" => "กรุณาเลือกประเภททุนวิจัย",
                "fund_cate.required" => "กรุณาเลือกลักษณะทุน",
                "fund_name.required" => "กรุณากรอกชื่อทุน",
                "support_resource.required" => "กรุณากรอกหน่วยงานที่สนับสนุน",
            ]
        );

        // 2. Create new fund with user_id
        Fund::create([
            "fund_name" => $request->fund_name,
            "fund_cate" => $request->fund_cate,
            "support_resource" => $request->support_resource,
            "user_id" => auth()->id(), // เพิ่ม user_id ของผู้ใช้ที่กำลังสร้างข้อมูล
        ]);

        // 3. Redirect with success message
        return redirect()
            ->route("funds.index")
            ->with("success", "เพิ่มทุนวิจัยสำเร็จ");
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'fund_name' => 'required',
    //         'fund_type' => 'required',
    //         'support_resource'=> 'required',
    //     ]);
    //return $request->all();
    //Fund::create($request->all());
    // $user = User::find(Auth::user()->id);
    //return $request->all();
    // if($request->has('pos')){
    //     $fund_type = $request->fund_type_etc ;

    // }else{
    //     $fund_type = $request->fund_type;

    // }

    //$fund = $request->all();
    //$fund['fund_type'] = $fund_type;
    //return $fund ;

    // $input=$request->all();
    // if($request->fund_type == 'ทุนภายนอก'){
    //     $input['fund_level']=null;
    // }
    // $user->fund()->Create($input);
    // return redirect()->route('funds.index')->with('success','fund created successfully.');
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // Load fund with all necessary relationships
            $fund = Fund::with(["category.fundType", "user"])->findOrFail($id);

            return view("funds.show", compact("fund"));
        } catch (\Exception $e) {
            \Log::error("Fund show error: " . $e->getMessage());
            return redirect()
                ->route("funds.index")
                ->with("error", "ไม่พบข้อมูลทุนวิจัยที่ต้องการแสดง");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            // Find the fund with relationships
            $fund = Fund::with(["category", "category.fundType"])->findOrFail(
                $id
            );

            // Get all fund types
            $fundType = FundsType::all();

            // Get categories for the current fund type
            $fundCategory = FundsCategory::where(
                "fund_type_id",
                $fund->category->fund_type_id
            )->get();

            return view(
                "funds.edit",
                compact("fund", "fundType", "fundCategory")
            );
        } catch (\Exception $e) {
            \Log::error("Fund edit error: " . $e->getMessage());
            return redirect()
                ->route("funds.index")
                ->with("error", "ไม่พบข้อมูลทุนวิจัยที่ต้องการแก้ไข");
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                "funds_type_id" => "required",
                "fund_cate" => "required",
                "fund_name" => "required",
                "support_resource" => "required",
            ],
            [
                "funds_type_id.required" => "กรุณาเลือกประเภททุนวิจัย",
                "fund_cate.required" => "กรุณาเลือกลักษณะทุน",
                "fund_name.required" => "กรุณากรอกชื่อทุน",
                "support_resource.required" => "กรุณากรอกหน่วยงานที่สนับสนุน",
            ]
        );

        try {
            $fund = Fund::findOrFail($id);
            $fund->update([
                "fund_name" => $request->fund_name,
                "fund_cate" => $request->fund_cate,
                "support_resource" => $request->support_resource,
            ]);

            return redirect()
                ->route("funds.index")
                ->with("success", "แก้ไขทุนวิจัยสำเร็จ");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with("error", "เกิดข้อผิดพลาดในการแก้ไขข้อมูล");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fund $fund)
    {
        $fund->delete();

        return redirect()
            ->route("funds.index")
            ->with("success", "Fund deleted successfully");
    }
}
