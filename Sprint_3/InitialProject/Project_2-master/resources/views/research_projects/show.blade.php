@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
<div class="container">
    <div class="card col-md-8" style="padding: 16px;">
        <div class="card-body">
            <h4 class="card-title">Research Projects Detail</h4>
            <p class="card-description">ข้อมูลรายละเอียดโครงการวิจัย</p>
            <div class="row">
                <p class="card-text col-sm-3"><b>ชื่อโครงการ</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->project_name }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>วันเริ่มต้นโครงการ</b></p>
                <p class="card-text col-sm-9">{{ \Carbon\Carbon::parse($researchProject->project_start)->format('d/m/Y') }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>วันสิ้นสุดโครงการ</b></p>
                <p class="card-text col-sm-9">{{ \Carbon\Carbon::parse($researchProject->project_end)->format('d/m/Y') }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>ประเภททุน</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->fund->category->fundType->name }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>ลักษณะทุน</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->fund->category->name }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>ทุน</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->fund->fund_name }}</p>
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>ปีที่ยื่น</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->project_year }}</p>
            </div>
            @if($researchProject->show_budget == 1)
            <div class="row">
                <p class="card-text col-sm-3"><b>จำนวนเงิน</b></p>
                <p class="card-text col-sm-9">{{ number_format($researchProject->budget, 2) }} บาท</p>
            </div>
            @endif
            <div class="row">
                <p class="card-text col-sm-3"><b>หน่วยงานที่รับผิดชอบ (ภายใน)</b></p>
                @php
                    $internalDepts = [];
                    foreach($researchProject->responsibleDepartmentResearchProject as $dept) {
                        if($dept->responsibleDepartment && ($dept->responsibleDepartment->type == 'ภายใน' || $dept->responsibleDepartment->type === null)) {
                            $internalDepts[] = $dept->responsibleDepartment->name;
                        }
                    }
                @endphp
                <p class="card-text col-sm-9">{{ implode(', ', $internalDepts) }}</p>
            </div>

            @php
                $externalDepts = [];
                foreach($researchProject->responsibleDepartmentResearchProject as $dept) {
                    if($dept->responsibleDepartment && $dept->responsibleDepartment->type == 'ภายนอก') {
                        $externalDepts[] = $dept->responsibleDepartment->name;
                    }
                }
            @endphp
            @if(count($externalDepts) > 0)
            <div class="row">
                <p class="card-text col-sm-3"><b>หน่วยงานที่รับผิดชอบ (ภายนอก)</b></p>
                <p class="card-text col-sm-9">{{ implode(', ', $externalDepts) }}</p>
            </div>
            @endif

            @if($researchProject->note)
            <div class="row">
                <p class="card-text col-sm-3"><b>รายละเอียดโครงการ</b></p>
                <p class="card-text col-sm-9">{{ $researchProject->note }}</p>
            </div>
            @endif
            <div class="row">
                <p class="card-text col-sm-3"><b>สถานะโครงการ</b></p>
                @if($researchProject->status == 1)
                <p class="card-text col-sm-9">ยื่นขอ</p>
                @elseif($researchProject->status == 2)
                <p class="card-text col-sm-9">ดำเนินการ</p>
                @else
                <p class="card-text col-sm-9">ปิดโครงการ</p>
                @endif
            </div>
            <div class="row">
                <p class="card-text col-sm-3"><b>ผู้รับผิดชอบโครงการ</b></p>
                @php
                    $projectHead = null;
                    foreach($researchProject->user as $user) {
                        if($user->pivot->role == 1) {
                            $projectHead = $user;
                            break;
                        }
                    }
                @endphp
                @if($projectHead)
                <p class="card-text col-sm-9">{{ $projectHead->position_th ?? '' }} {{ $projectHead->fname_th }} {{ $projectHead->lname_th }}</p>
                @else
                <p class="card-text col-sm-9">-</p>
                @endif
            </div>

            @php
                $internalMembers = [];
                foreach($researchProject->user as $user) {
                    if($user->pivot->role == 2) {
                        $internalMembers[] = ($user->position_th ?? '') . ' ' . $user->fname_th . ' ' . $user->lname_th;
                    }
                }

                $externalMembers = [];
                foreach($researchProject->outsider as $outsider) {
                    if($outsider->pivot->role == 2) {
                        $externalMembers[] = ($outsider->title_name ?? '') . ' ' . $outsider->fname . ' ' . $outsider->lname;
                    }
                }
            @endphp

            @if(count($internalMembers) > 0)
            <div class="row">
                <p class="card-text col-sm-3"><b>ผู้ร่วมโครงการ (ภายใน)</b></p>
                <p class="card-text col-sm-9">{{ implode(', ', $internalMembers) }}</p>
            </div>
            @endif

            @if(count($externalMembers) > 0)
            <div class="row">
                <p class="card-text col-sm-3"><b>ผู้ร่วมโครงการ (ภายนอก)</b></p>
                <p class="card-text col-sm-9">{{ implode(', ', $externalMembers) }}</p>
            </div>
            @endif

            <div class="pull-right mt-5">
                <a class="btn btn-primary" href="{{ route('researchProjects.index') }}">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
