<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Source_data;
use App\Models\User;
use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WoscallController extends Controller
{
    /**
     * ค้นหาผู้แต่งผ่าน Researcher API เพื่อหา ResearcherID (rid) จากนั้น
     * ดึง UID ของเอกสารทั้งหมดจาก /researchers/{rid}/documents แล้วใช้ UID
     * เรียก Web of Science Starter API เพื่อดึงรายละเอียดเอกสารพื้นฐาน
     * และใช้ Web of Science API Expanded เพื่อดึงข้อมูล abstract
     * แล้วนำข้อมูลมาแมปและบันทึกในฐานข้อมูล
     *
     * หมายเหตุ: ใช้ API Key แยกสำหรับ Researcher API, Starter API และ Expanded API
     *
     * @param  string  $id  รหัสผู้ใช้ (encrypted)
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        // --- ขั้นตอนที่ 1: ถอดรหัส id และดึงข้อมูลผู้ใช้ ---
        $id = Crypt::decrypt($id);
        $user = User::find($id);
        if (!$user) {
            Log::error("ไม่พบผู้ใช้ (User not found) โดยใช้ id: $id");
            return redirect()->back()->withErrors("ไม่พบข้อมูลผู้ใช้");
        }

        // --- กำหนด API Keys ---
        $researcherApiKey = ''; // API Key สำหรับ Web of Science Researcher API
        $starterApiKey = '391081f856be859e30012fb432440b5da159892e'; // API Key สำหรับ Web of Science Starter API
        $expandedApiKey = ''; // API Key สำหรับ Web of Science API Expanded

        // --- ขั้นตอนที่ 2: ค้นหา ResearcherID (rid) ด้วย Researcher API ---
        $researcherEndpoint = 'https://api.clarivate.com/apis/wos-researcher/researchers';
        // สร้าง query โดยใช้ first name และ last name (syntax: FirstName~"xxx" AND LastName~"yyy")
        $q = 'FirstName~"' . $user->fname_en . '" AND LastName~"' . $user->lname_en . '"';
        $params = [
            'q' => $q,
            'limit' => 1,
            'page' => 1,
        ];

        $researcherResponse = Http::withHeaders([
            'X-ApiKey' => $researcherApiKey,
        ])->get($researcherEndpoint, $params);

        if ($researcherResponse->failed()) {
            Log::warning("การเรียก Researcher API ล้มเหลว: " . $researcherResponse->body());
            return redirect()->back()->withErrors("ไม่สามารถดึงข้อมูล Researcher ได้");
        }

        $researcherData = $researcherResponse->json();
        if (!isset($researcherData['records']) || count($researcherData['records']) == 0) {
            Log::warning("ไม่พบข้อมูล Researcher สำหรับ user id: {$user->id}");
            return redirect()->back()->withErrors("ไม่พบข้อมูล Researcher ใน Web of Science");
        }

        $rid = $researcherData['records'][0]['rid'] ?? null;
        if (!$rid) {
            Log::warning("ไม่พบ ResearcherID (rid) ในข้อมูลสำหรับ user id: {$user->id}");
            return redirect()->back()->withErrors("ไม่พบ ResearcherID ในข้อมูล");
        }
        Log::info("พบ ResearcherID: $rid สำหรับ user id: {$user->id}");

        // --- ขั้นตอนที่ 3: ดึงเอกสารของ Researcher ผ่าน Researcher API ---
        $researcherDocumentsEndpoint = 'https://api.clarivate.com/apis/wos-researcher/researchers/' . $rid . '/documents';
        $docParams = [
            'limit' => 50, // สูงสุด 50 ต่อหน้า
            'page' => 1,
        ];

        $documentsResponse = Http::withHeaders([
            'X-ApiKey' => $researcherApiKey,
        ])->get($researcherDocumentsEndpoint, $docParams);

        if ($documentsResponse->failed()) {
            Log::warning("การเรียกเอกสารสำหรับ ResearcherID $rid ล้มเหลว: " . $documentsResponse->body());
            return redirect()->back()->withErrors("ไม่สามารถดึงเอกสารของนักวิจัยได้");
        }

        $documentsData = $documentsResponse->json();
        if (!isset($documentsData['records']) || count($documentsData['records']) == 0) {
            Log::warning("ไม่พบเอกสารสำหรับ ResearcherID $rid");
            return redirect()->back()->withErrors("ไม่พบเอกสารสำหรับนักวิจัยนี้");
        }

        // --- ขั้นตอนที่ 4: สำหรับแต่ละ UID ของเอกสารที่ได้จาก Researcher API ---
        foreach ($documentsData['records'] as $docRecord) {
            $uid = $docRecord['uid'] ?? null;
            if (!$uid) {
                continue;
            }

            // --- ขั้นตอนที่ 5: ใช้ UID ไปเรียกรายละเอียดเอกสารจาก Starter API ---
            // เรียกใช้ endpoint /documents/{uid} พร้อม parameter detail=full
            $documentDetailEndpoint = 'https://api.clarivate.com/apis/wos-starter/v1/documents/' . urlencode($uid);
            $detailParams = [
                'detail' => 'full'
            ];
            $documentDetailResponse = Http::withHeaders([
                'X-ApiKey' => $starterApiKey,
            ])->get($documentDetailEndpoint, $detailParams);

            if ($documentDetailResponse->failed()) {
                Log::warning("การเรียกรายละเอียดเอกสารสำหรับ UID $uid ล้มเหลว: " . $documentDetailResponse->body());
                continue;
            }

            $documentDetail = $documentDetailResponse->json();

            // --- ขั้นตอนที่ 6: ดึงข้อมูล Abstract โดยใช้ Expanded API ---
            // เรียกใช้ endpoint /id/{uniqueId} จาก Expanded API เพื่อดึงข้อมูลที่ครบถ้วน (รวมถึง Abstract)
            $expandedEndpoint = 'https://api.clarivate.com/id/' . urlencode($uid);
            $expandedParams = [
                'databaseId' => 'WOS',
                'lang' => 'en',
                'optionView' => 'FR', // รับข้อมูล Full Record
            ];
            $expandedResponse = Http::withHeaders([
                'X-ApiKey' => $expandedApiKey,
            ])->get($expandedEndpoint, $expandedParams);

            $abstractText = null;
            if ($expandedResponse->successful()) {
                $expandedData = $expandedResponse->json();
                // เดินตามโครงสร้างเพื่อนำข้อมูล abstract:
                // Data -> Records -> records -> REC -> static_data -> fullrecord_metadata ->
                // abstracts -> abstract -> abstract_text -> p
                if (isset($expandedData['Data']['Records']['records'][0]['REC']['static_data']['fullrecord_metadata']['abstracts']['abstract']['abstract_text']['p'])) {
                    $abstractText = $expandedData['Data']['Records']['records'][0]['REC']['static_data']['fullrecord_metadata']['abstracts']['abstract']['abstract_text']['p'];
                    // หาก abstract เป็น array ให้รวมเป็น string
                    if (is_array($abstractText)) {
                        $abstractText = implode(" ", $abstractText);
                    }
                }
            } else {
                Log::warning("การเรียก Abstract จาก Expanded API ล้มเหลว สำหรับ UID $uid: " . $expandedResponse->body());
            }

            // --- ขั้นตอนที่ 7: แมปข้อมูลเอกสารและบันทึกในฐานข้อมูล ---
            // สมมติว่า Starter API ส่งข้อมูลพื้นฐาน เช่น title, publishYear, citations, types, sourceTypes, และลิงก์ record
            $title = $documentDetail['title'] ?? null;
            if (!$title) {
                continue;
            }

            // หากมี paper ที่มีชื่อเดียวกันอยู่แล้ว ให้ข้าม
            $existingPaper = Paper::where('paper_name', $title)->first();
            if ($existingPaper) {
                Log::info("พบ paper อยู่แล้ว: $title");
                continue;
            }

            $paper = new Paper;
            $paper->paper_name = $title;
            $paper->paper_url = $documentDetail['links']['record'] ?? null;
            $paper->paper_yearpub = $documentDetail['source']['publishYear'] ?? null;
            $paper->paper_citation = $documentDetail['citations']['count'] ?? null;
            $paper->paper_type = isset($documentDetail['types']) && is_array($documentDetail['types'])
                ? implode(', ', $documentDetail['types'])
                : $documentDetail['types'] ?? null;

            $paper->paper_subtype = isset($documentDetail['sourceTypes']) && is_array($documentDetail['sourceTypes'])
                ? implode(', ', $documentDetail['sourceTypes'])
                : $documentDetail['sourceTypes'] ?? null;

            // กำหนด Abstract จากข้อมูลที่ดึงได้จาก Expanded API
            $paper->abstract = $abstractText;

            $paper->save();
            Log::info("สร้าง paper ใหม่จาก UID $uid", [
                'paper_id' => $paper->id,
                'paper_title' => $paper->paper_name,
            ]);

            // --- ขั้นตอนที่ 8: แนบ Source_data สำหรับ WOS Starter ---
            try {
                // สมมติว่า Source_data สำหรับ WOS Starter มี id = 4
                $source = Source_data::findOrFail(4);
                $paper->source()->sync([$source->id]);
                Log::info("แนบ Source_data (WOS Starter) สำเร็จ", [
                    'paper_id' => $paper->id,
                    'source_id' => $source->id,
                ]);
            } catch (\Exception $e) {
                Log::warning("ไม่พบ Source_data สำหรับ WOS Starter: " . $e->getMessage());
            }

            // --- ขั้นตอนที่ 9: ประมวลผลและแนบผู้แต่ง (Authors) ---
            // สมมติว่าในรายละเอียดเอกสารมีข้อมูลผู้แต่งใน key "Authors"
            if (isset($documentDetail['Authors'])) {
                $authorsData = $documentDetail['Authors'];
                if (!is_array($authorsData)) {
                    // ถ้าเป็น string ให้แยกด้วย semicolon
                    $authorsData = explode(';', $authorsData);
                }
                $x = 1;
                $length = count($authorsData);
                foreach ($authorsData as $authorName) {
                    $authorName = trim($authorName);
                    if (empty($authorName)) {
                        continue;
                    }
                    // แยกชื่อผู้แต่งโดยสมมติว่า คำแรกคือ given name ส่วนที่เหลือคือ surname
                    $nameParts = explode(" ", $authorName);
                    $givenName = $nameParts[0] ?? "";
                    $surname = count($nameParts) > 1 ? implode(" ", array_slice($nameParts, 1)) : "";

                    // กำหนด author type: 1 = first, 3 = last, 2 = middle
                    $authorType = ($x === 1) ? 1 : (($x === $length) ? 3 : 2);

                    // ตรวจสอบว่ามี Teacher (User) ที่ตรงกับชื่อหรือไม่
                    $existingUser = User::where('fname_en', $givenName)
                        ->where('lname_en', $surname)
                        ->first();

                    if (!$existingUser) {
                        // หากไม่มี สร้าง Author ใหม่
                        $authorModel = Author::firstOrCreate([
                            'author_fname' => $givenName,
                            'author_lname' => $surname,
                        ]);
                        $paper->author()->attach($authorModel->id, ['author_type' => $authorType]);
                        Log::info("แนบ Author สำเร็จ", [
                            'paper_id' => $paper->id,
                            'author_id' => $authorModel->id,
                            'author_type' => $authorType,
                        ]);
                    } else {
                        // หากมีอยู่แล้ว แนบในฐานะ Teacher
                        $paper->teacher()->attach($existingUser->id, ['author_type' => $authorType]);
                        Log::info("แนบ Teacher สำเร็จ", [
                            'paper_id' => $paper->id,
                            'teacher_id' => $existingUser->id,
                            'author_type' => $authorType,
                        ]);
                    }
                    $x++;
                }
            }
        }

        return redirect()->back()->with('success', 'บันทึกข้อมูลเอกสารจาก Researcher, Starter และ Expanded APIs สำเร็จ');
    }
}
