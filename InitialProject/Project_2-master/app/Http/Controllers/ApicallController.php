<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ApicallController extends Controller
{
    /**
     * เรียกใช้ Scopus และ Google Scholar API พร้อมบันทึกข้อมูล
     *
     * @param string $id (encrypted user id)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create($id)
    {
        $decryptedId = Crypt::decrypt($id);

        // เรียกใช้งาน ScopuscallController
        $scopusController = app(\App\Http\Controllers\ScopuscallController::class);
        $scopusResponse = $scopusController->create($id);
        Log::info("เรียก Scopus API สำเร็จสำหรับ user id: {$decryptedId}");

        // เรียกใช้งาน GooglescholarcallController
        $googlescholarController = app(\App\Http\Controllers\GooglescholarcallController::class);
        $googlescholarRequest = $googlescholarController->create($id);
        Log::info("เรียก Google Scholar API สำเร็จสำหรับ user id: {$decryptedId}");

        // หลังจากเรียกทั้งสองแล้ว ให้ redirect กลับไปที่หน้าที่ต้องการ
        return redirect()->back()->with('success', 'เรียกข้อมูลจาก Scopus และ Google Scholar สำเร็จ');
    }
}
