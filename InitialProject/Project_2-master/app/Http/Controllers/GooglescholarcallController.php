<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Source_data;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Traits\ProcessesAuthors;

class GooglescholarcallController extends Controller
{

    /**
     * ดึงข้อมูลจาก Google Scholar โดยใช้ SerpApi:
     * 1. ค้นหา author profile ด้วย engine google_scholar_profiles (parameter: mauthors)
     * 2. ดึงบทความทั้งหมดจาก Google Scholar Author API โดยใช้ author_id
     * 3. สำหรับแต่ละบทความ หากมี citation_id ให้เรียก Citation API (view_op=view_citation) เพื่อดึงข้อมูล citation เพิ่มเติม
     * 4. แมปและบันทึกข้อมูลลงในฐานข้อมูล (อิงจาก Model Paper)
     * 5. ประมวลผลและแนบผู้แต่ง (authors) โดยเรียกใช้ฟังก์ชัน processAuthors()
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
        $fullName = trim($user->fname_en . ' ' . $user->lname_en);
        $apiKey = 'd240eff57427c044e985afb54fe3e6f6eb03bfc48141c5657aff9026ff7336a7';

        // --- ขั้นตอนที่ 2: ค้นหา author profile ด้วย google_scholar_profiles ---
        $profileParams = [
            'engine'   => 'google_scholar_profiles',
            'mauthors' => $fullName,
            'api_key'  => $apiKey,
            'hl'       => 'en'
        ];
        $profileResponse = Http::get('https://serpapi.com/search.json', $profileParams)->json();
        if (!isset($profileResponse['profiles']) || count($profileResponse['profiles']) == 0) {
            Log::warning("ไม่พบข้อมูลผู้แต่งจาก Google Scholar สำหรับ user id: {$user->id}");
            return redirect()->back()->withErrors("ไม่พบข้อมูลผู้แต่งใน Google Scholar");
        }
        $firstProfile = $profileResponse['profiles'][0];
        if (!isset($firstProfile['author_id'])) {
            Log::warning("ไม่พบ author_id ในผลการค้นหาผู้แต่งสำหรับ user id: {$user->id}");
            return redirect()->back()->withErrors("ไม่พบข้อมูลผู้แต่งใน Google Scholar");
        }
        $authorId = $firstProfile['author_id'];
        Log::info("พบ Google Scholar author_id: $authorId สำหรับ user id: {$user->id}");

        // --- ขั้นตอนที่ 3: ดึงบทความทั้งหมดจาก Google Scholar Author API ---
        $authorParams = [
            'engine'    => 'google_scholar_author',
            'author_id' => $authorId,
            'api_key'   => $apiKey,
            'hl'        => 'en'
        ];
        $authorResponse = Http::get('https://serpapi.com/search.json', $authorParams)->json();
        if (!isset($authorResponse['articles']) || count($authorResponse['articles']) == 0) {
            Log::warning("ไม่พบบทความจาก Google Scholar สำหรับ author_id: $authorId");
            return redirect()->back()->withErrors("ไม่พบข้อมูลบทความจาก Google Scholar");
        }
        $articles = $authorResponse['articles'];

        // --- ขั้นตอนที่ 4: ประมวลผลแต่ละบทความ ---
        foreach ($articles as $article) {
            // ตรวจสอบว่ามี title ของบทความ
            if (!isset($article['title'])) {
                continue;
            }
            $title = $article['title'];

            // หากมี paper ที่มีชื่อเดียวกันอยู่แล้ว ให้ข้าม
            $existingPaper = Paper::where('paper_name', $title)->first();
            if ($existingPaper) {
                Log::info("พบ paper อยู่แล้ว: $title");
                continue;
            }

            // สร้าง record ใหม่สำหรับ Paper และแมปข้อมูลพื้นฐาน
            $paper = new Paper;
            $paper->paper_name = $title;
            $paper->paper_url  = $article['link'] ?? null;
            $paper->paper_yearpub = $article['year'] ?? null; // หากมีใน response

            // --- ขั้นตอนที่ 5: ค้นหา citation ของบทความ (ถ้ามี citation_id) ---
            if (isset($article['citation_id']) && !empty($article['citation_id'])) {
                $citationParams = [
                    'engine'      => 'google_scholar_author',
                    'citation_id' => $article['citation_id'],
                    'view_op'     => 'view_citation',
                    'api_key'     => $apiKey,
                    'hl'          => 'en'
                ];
                $citationResponse = Http::get('https://serpapi.com/search.json', $citationParams)->json();

                $paper->abstract = $citationResponse['citation']['description'] ?? null;

                if (isset($citationResponse['citation']['journal'])) {
                    $paperType = "Journal";
                } elseif (isset($citationResponse['citation']['conference'])) {
                    $paperType = "Conference Proceeding";
                } else {
                    $paperType = null;
                }

                $paper->paper_type = $paperType;
                $paper->paper_subtype = "Article";
                $paper->paper_sourcetitle = $citationResponse['citation']['journal'] ?? null;
                $paper->paper_volume = $citationResponse['citation']['volume'] ?? null;
                $paper->paper_issue = $citationResponse['citation']['issue'] ?? null;
                $paper->paper_citation = $citationResponse['citation']['total_citations']['cited_by']['total'] ?? null;
                $paper->paper_page = $citationResponse['citation']['pages'] ?? null;

                // แปลง publication_date ให้เหลือปีเดียว
                $pubDate = $citationResponse['citation']['publication_date'] ?? null;
                if ($pubDate) {
                    try {
                        $paper->paper_yearpub = Carbon::parse($pubDate)->format('Y');
                    } catch (\Exception $e) {
                        $paper->paper_yearpub = null;
                    }
                }
            } else {
                $paper->paper_citation = null;
                // กำหนด paper_subtype เป็น "Article" ถ้าไม่มี citation
                $paper->paper_subtype = "Article";
            }

            // --- ขั้นตอนที่ 6: บันทึก Paper ลงในฐานข้อมูล ---
            $paper->save();
            Log::info("สร้าง paper ใหม่จาก Google Scholar สำเร็จ", [
                'paper_id'    => $paper->id,
                'paper_title' => $paper->paper_name
            ]);

            // --- ขั้นตอนที่ 7: แนบ Source_data สำหรับ Google Scholar ---
            try {
                $source = Source_data::findOrFail(3);
                $paper->source()->sync([$source->id]);
                Log::info("แนบ Source_data (Google Scholar) สำเร็จ", [
                    'paper_id'  => $paper->id,
                    'source_id' => $source->id,
                ]);
            } catch (\Exception $e) {
                Log::warning("ไม่พบ Source_data สำหรับ Google Scholar: " . $e->getMessage());
            }

            // --- ขั้นตอนที่ 8: ประมวลผลผู้แต่ง (authors) ---
            if (isset($article['authors'])) {
                // ตรวจสอบว่า $article['authors'] เป็นอาเรย์หรือไม่
                if (is_array($article['authors'])) {
                    $authorsData = $article['authors'];
                } else {
                    // ถ้าไม่ใช่อาเรย์ (เช่น เป็น string) ให้แปลงเป็นอาเรย์
                    $nameStr = trim($article['authors']);
                    if (strpos($nameStr, ',') !== false) {
                        $names = array_map('trim', explode(',', $nameStr));
                    } else {
                        $names = [$nameStr];
                    }
                    $authorsData = [];
                    foreach ($names as $name) {
                        // สมมติว่าการแบ่งชื่อด้วยช่องว่าง: คำแรกคือ given name ส่วนที่เหลือคือ surname
                        $parts = preg_split('/\s+/', $name);
                        $givenName = $parts[0] ?? "";
                        $surname = count($parts) > 1 ? implode(" ", array_slice($parts, 1)) : "";
                        $authorsData[] = [
                            'ce:given-name' => $givenName,
                            'ce:surname'    => $surname
                        ];
                    }
                }
                // ประมวลผลและแนบผู้แต่ง
                $x = 1;
                $length = count($authorsData);
                foreach ($authorsData as $authorData) {
                    $givenName = $authorData['ce:given-name'] ?? '';
                    $surname = $authorData['ce:surname'] ?? '';

                    // ค้นหาผู้ใช้ในระบบ
                    $userExists = \App\Models\User::where('fname_en', $givenName)
                        ->orWhere('lname_en', $surname)
                        ->first();

                    $authorType = ($x === 1) ? 1 : (($x === $length) ? 3 : 2);

                    if (!$userExists) {
                        // หากไม่มี ให้สร้าง Author ใหม่
                        $authorModel = \App\Models\Author::firstOrCreate([
                            'author_fname' => $givenName,
                            'author_lname' => $surname
                        ]);
                        $paper->author()->attach($authorModel->id, ['author_type' => $authorType]);
                        Log::info("แนบ Author สำเร็จ", [
                            'paper_id' => $paper->id,
                            'author_id' => $authorModel->id,
                            'author_type' => $authorType
                        ]);
                    } else {
                        // หากมีอยู่แล้ว ให้แนบกับ teacher
                        $paper->teacher()->attach($userExists->id, ['author_type' => $authorType]);
                        Log::info("แนบ Teacher สำเร็จ", [
                            'paper_id' => $paper->id,
                            'teacher_id' => $userExists->id,
                            'author_type' => $authorType
                        ]);
                    }
                    $x++;
                }
            }
        }

        return redirect()->back()->with('success', 'บันทึกข้อมูลจาก Google Scholar สำเร็จ');
    }
}
