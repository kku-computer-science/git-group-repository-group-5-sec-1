<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Paper;
use App\Models\ResearchGroup;
use Illuminate\Http\Request;

class ResearchGroupDetailController extends Controller
{
    public function request($id)
    {   // ดึงข้อมูล ResearchGroup พร้อมกับ User และ Paper โดยการจัดเรียง Paper ตามปีที่ตีพิมพ์ล่าสุด
        $resgd = ResearchGroup::with(['User.paper' => function ($query) {
            return $query->orderBy('paper_yearpub','DESC');
        }])->where('id','=',$id)->first(); // ใช้ first() เพราะเราต้องการแค่ 1 กลุ่มวิจัย
         // ดึง banner_image จาก research_groups table
        $rg = ResearchGroup::select('banner_image')->where('id', $id)->first();
        //return $resgd;
        // $std = ResearchGroup::hasRole('student')::with(['User.paper' => function ($query) {
        //     return $query->orderBy('paper_yearpub','DESC');
        // }])->where('id','=',$id)->get();
        // $ref = $resgd[0]->user[1]->fname_en;
        // $rel = $resgd[0]->user[1]->lname_en;
        // $author = Author::where([['author_fname', '=', $ref], ['author_lname', '=', $rel]])->get();
        //return  $author;

        // $author = Paper::whereHas('author', function($q){
        //     $q->where('author_fname', '=', 'Pongsathon');
        // })->get();
        // $author = collect($author);
        //return  $author;

        // เช็คถ้าพบข้อมูลกลุ่มวิจัย
    if (!$resgd) {
        return redirect()->back()->with('error', 'ไม่พบข้อมูลกลุ่มวิจัย');}

        return view('researchgroupdetail', compact('resgd'));
        //return $resgd;

    }

    
}
