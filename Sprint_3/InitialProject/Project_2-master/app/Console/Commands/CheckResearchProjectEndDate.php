<?php

namespace App\Console\Commands;

use App\Models\ResearchProject;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckResearchProjectEndDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "projects:check-end-date";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Check projects end date and update status automatically";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // รับวันที่ปัจจุบัน
        $today = Carbon::now()->format("Y-m-d");

        // หาโครงการที่มีวันสิ้นสุดตรงกับหรือก่อนวันปัจจุบัน และสถานะยังเป็น "ดำเนินการ" (2)
        $projects = ResearchProject::where("status", 2)
            ->whereDate("project_end", "<=", $today)
            ->get();

        // จำนวนโครงการที่อัพเดต
        $count = 0;

        foreach ($projects as $project) {
            // เปลี่ยนสถานะเป็น "ปิดโครงการ" (3)
            $project->status = 3;
            $project->save();

            $count++;
        }

        $this->info("Updated status for {$count} projects.");

        return 0;
    }
}
