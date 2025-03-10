<<<<<<< HEAD
<<<<<<< HEAD
=======

>>>>>>> 6976c4b6dc8f1098d5d6b3d7a462e94baee73759
=======
{{-- @extends('layouts.layout')
<style>
    .name {

        font-size: 20px;

    }
</style>
@section('content')
<div class="container card-4 mt-5">
    <div class="card">
        @foreach ($resgd as $rg)
        <div class="row g-0">
            <div class="col-md-4">
                <div class="card-body">
                    <img src="{{asset('img/'.$rg->group_image)}}" alt="...">
                    <h1 class="card-text-1"> Laboratory Supervisor </h1>
                    <h2 class="card-text-2">
                        @foreach ($rg->user as $r)
                        @if ($r->hasRole('teacher'))
                        @if (app()->getLocale() == 'en' and $r->academic_ranks_en == 'Lecturer' and $r->doctoral_degree == 'Ph.D.')
                             {{ $r->{'fname_'.app()->getLocale()} }} {{ $r->{'lname_'.app()->getLocale()} }}, Ph.D.
                            <br>
                            @elseif(app()->getLocale() == 'en' and $r->academic_ranks_en == 'Lecturer')
                            {{ $r->{'fname_'.app()->getLocale()} }} {{ $r->{'lname_'.app()->getLocale()} }}
                            <br>
                            @elseif(app()->getLocale() == 'en' and $r->doctoral_degree == 'Ph.D.')
                            {{ str_replace('Dr.', ' ', $r->{'position_'.app()->getLocale()}) }} {{ $r->{'fname_'.app()->getLocale()} }} {{ $r->{'lname_'.app()->getLocale()} }}, Ph.D.
                            <br>
                            @else                            
                            {{ $r->{'position_'.app()->getLocale()} }} {{ $r->{'fname_'.app()->getLocale()} }} {{ $r->{'lname_'.app()->getLocale()} }}
                            <br>
                            @endif
                        
                        @endif
                        @endforeach
                    </h2>
                    <h1 class="card-text-1"> Student </h1>
                    <h2 class="card-text-2">
                        @foreach ($rg->user as $user)
                        @if ($user->hasRole('student'))
                        {{$user->{'position_'.app()->getLocale()} }} {{$user->{'fname_'.app()->getLocale()} }} {{$user->{'lname_'.app()->getLocale()} }}
                        <br>
                        @endif
                        @endforeach
                    </h2>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"> {{ $rg->{'group_name_'.app()->getLocale()} }}</>
                    </h5>
                    <h3 class="card-text">{{$rg->{'group_detail_'.app()->getLocale()} }}
                    </h3>
                </div>
                
            </div>
            @endforeach
            <!-- <div id="loadMore">
                <a href="#"> Load More </a>
            </div> -->
        </div>

    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
    $(document).ready(function() {
        $(".moreBox").slice(0, 1).show();
        if ($(".blogBox:hidden").length != 0) {
            $("#loadMore").show();
        }
        $("#loadMore").on('click', function(e) {
            e.preventDefault();
            $(".moreBox:hidden").slice(0, 1).slideDown();
            if ($(".moreBox:hidden").length == 0) {
                $("#loadMore").fadeOut('slow');
            }
        });
    });
</script>

@stop
<!-- <div class="card-body-research">
                    <p>Research</p>
                    <table class="table">
                        @foreach ($rg->user as $user)
                        
                        <thead>
                            <tr>
                                <th><b class="name">{{$user->{'position_'.app()->getLocale()} }} {{$user->{'fname_'.app()->getLocale()} }} {{$user->{'lname_'.app()->getLocale()} }}</b></th>
                            </tr>
                            @foreach ($user->paper->sortByDesc('paper_yearpub') as $p)
                            <tr class="hidden">
                                <th>
                                    <b><math>{!! html_entity_decode(preg_replace('<inf>', 'sub', $p->paper_name)) !!}</math></b> (
                                    <link>@foreach ($p->teacher as $teacher){{$teacher->fname_en}} {{$teacher->lname_en}},
                                    @endforeach
                                    @foreach ($p->author as $author){{$author->author_fname}} {{$author->author_lname}}@if (!$loop->last),@endif
                                    @endforeach</link>), {{$p->paper_sourcetitle}}, {{$p->paper_volume}},
                                    {{ $p->paper_yearpub }}.
                                    <a href="{{$p->paper_url}} " target="_blank">[url]</a> <a href="https://doi.org/{{$p->paper_doi}}" target="_blank">[doi]</a>
                                </th>
                            </tr>
                            @endforeach
                        </thead>
                        @endforeach
                    </table>
                </div> --> 

                 --}}







{{-- @extends('layouts.layout')
@section('content')
    <div class="container mt-5">
        <!-- ข้อมูลกลุ่ม -->
   
            <div >
                <h3>{{ $resgd->group_name_th }}</h3>
                <h4>{{ $resgd->group_name_en }}</h4>
            </div>
            
       

        <!-- หัวหน้ากลุ่มวิจัย -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>หัวหน้ากลุ่มวิจัย</h4>
            </div>
            <div class="card-body">
                @foreach ($resgd->user as $user)
                    @if ($user->pivot->role == 1)
                        <div class="row">
                            <div class="col-md-3">
                                @if ($user->picture)
                                    <img src="{{ asset($user->picture) }}" class="img-fluid rounded-circle">
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h5>{{ $user->academic_ranks_th }} {{ $user->fname_th }} {{ $user->lname_th }}</h5>
                                <p>{{ $user->academic_ranks_en }} {{ $user->fname_en }} {{ $user->lname_en }}</p>
                                <p>Email: {{ $user->email }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- สมาชิกกลุ่มวิจัย -->
        <div class="card">
            <div class="card-header">
                <h4>สมาชิกกลุ่มวิจัย</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($resgd->user as $user)
                        @if ($user->pivot->role == 2)
                            <div class="col-md-6 mb-4">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        @if ($user->picture)
                                            <img src="{{ asset($user->picture) }}" class="rounded-circle"
                                                style="width: 100px;">
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>{{ $user->academic_ranks_th }} {{ $user->fname_th }} {{ $user->lname_th }}
                                        </h5>
                                        <p>{{ $user->academic_ranks_en }} {{ $user->fname_en }} {{ $user->lname_en }}</p>
                                        <p>Email: {{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- แสดงผลงานวิจัย -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>ผลงานวิจัย</h4>
            </div>
            <div class="card-body">
                @foreach ($resgd->user as $user)
                    @if ($user->paper->count() > 0)
                        <h5>{{ $user->fname_en }} {{ $user->lname_en }}</h5>
                        <ul>
                            @foreach ($user->paper as $paper)
                                <li>
                                    {{ $paper->paper_name }} ({{ $paper->paper_yearpub }})
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection --}}



>>>>>>> Nantapong_1341
@extends('layouts.layout')
@section('content')
<div class="container mt-5">
    <!-- ข้อมูลกลุ่ม -->
<<<<<<< HEAD
    
    <div class="image-container">
        @if($resgd->banner_image)
        <img src="{{asset('img/'.$resgd->banner_image)}}" alt="Cinque Terre" width="100%" height="300">
        <div class="image-text" >
            <h1 style="color:white;">{{ $resgd->group_name_en }}</h1>
            
        </div>
        @endif
    </div>
   
    <!-- Research rationale -->
    <div class="card mb-4">
        <div class="card-body">
            <h2>Research rationale</h2>
            @if($resgd->group_detail_en)
                <p>{!! nl2br($resgd->group_detail_en) !!}</p>
=======
    <div class="image-container">
        <img src="img\1651340170.png" alt="Cinque Terre" width="100%" height="300">
        <div class="image-text" >
            <h1 style="color:white;">{{ $resgd->group_name_th }}</h1>
            
        </div>
        
    </div>
    <!-- Research rationale -->
    <div class="card mb-4">
        <div class="card-body">
            <h2>Research rationale ของกลุ่มวิจัย</h2>
            @if($resgd->group_detail_th)
                <p>{!! nl2br($resgd->group_detail_th) !!}</p>
>>>>>>> Nantapong_1341
            @endif
        </div>
    </div>

    <!-- หัวข้อวิจัยที่เป็นจุดเน้นของกลุ่ม -->
    <div class="card mb-4">
        <div class="card-body">
<<<<<<< HEAD
            <h2>Main research areas/topics </h2>
            @if($resgd->group_desc_en)
                <ul>
                    @foreach(explode("\n", $resgd->group_desc_en) as $topic)
=======
            <h2>หัวข้อวิจัยที่เป็นจุดเน้นของกลุ่ม</h2>
            @if($resgd->group_desc_th)
                <ul>
                    @foreach(explode("\n", $resgd->group_desc_th) as $topic)
>>>>>>> Nantapong_1341
                        @if(trim($topic))
                            <li>{!! $topic !!}</li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- สมาชิกของกลุ่มวิจัย -->
    <div class="card mb-4">
        <div class="card-body">
<<<<<<< HEAD
            <h2>Members of Research Group</h2>
            
            <!-- หัวหน้ากลุ่มวิจัย -->
            <h3>Head of Reseach Group</h3>
            @foreach($resgd->user as $user)
                @if($user->pivot->role == 5)
                    <p>{{ $user->academic_ranks_en }} {{ $user->fname_en }} {{ $user->lname_en }} {{ $user->department_name_en }}</p>
=======
            <h2>สมาชิกของกลุ่มวิจัย</h2>
            
            <!-- หัวหน้ากลุ่มวิจัย -->
            <h3>หัวหน้ากลุ่มวิจัย</h3>
            @foreach($resgd->user as $user)
                @if($user->pivot->role == 1)
                    <p>{{ $user->academic_ranks_th }} {{ $user->fname_th }} {{ $user->lname_th }} {{ $user->department_name_th }}</p>
>>>>>>> Nantapong_1341
                @endif
            @endforeach

            <!-- สมาชิก -->
<<<<<<< HEAD
            <h3>Members</h3>
            <ul>
                @foreach($resgd->user as $user)
                    @if($user->pivot->role == 2)
                        <li>{{ $user->academic_ranks_en }} {{ $user->fname_en }} {{ $user->lname_en }} {{ $user->department_name_en }}</li>
=======
            <h3>สมาชิก</h3>
            <ul>
                @foreach($resgd->user as $user)
                    @if($user->pivot->role == 2)
                        <li>{{ $user->academic_ranks_th }} {{ $user->fname_th }} {{ $user->lname_th }} {{ $user->department_name_th }}</li>
>>>>>>> Nantapong_1341
                    @endif
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Contact person -->
    <div class="card mb-4">
        <div class="card-body">
            <h2>Contact person</h2>
            @foreach($resgd->user as $user)
<<<<<<< HEAD
                @if($user->pivot->role == 5)
                    <p>{{ $user->academic_ranks_en }} {{ $user->fname_en }} {{ $user->lname_en }}, {{ $user->email }}</p>
                    @if($user->picture)
                        <img src="{{ asset($user->picture) }}" alt="Contact Person" style="max-width: 300px;">
=======
                @if($user->pivot->role == 1)
                    <p>{{ $user->academic_ranks_th }} {{ $user->fname_th }} {{ $user->lname_th }}, {{ $user->email }}</p>
                    @if($user->picture)
                        <img src="{{ asset($user->picture) }}" alt="Contact Person" style="max-width: 200px;">
>>>>>>> Nantapong_1341
                    @endif
                @endif
            @endforeach
        </div>
    </div>
</div>

<style>
h2 {
    font-size: 24px;
    margin-bottom: 20px;
}
h3 {
    font-size: 20px;
    margin-top: 15px;
    margin-bottom: 10px;
}
ul {
    padding-left: 20px;
}
.card {
    border: none;
    box-shadow: none;
}
.card-body {
    padding: 20px 0;
}

.center {
  position: center;
  /* top: 50%;
  width: 100%; */
  text-align: center;
  font-size: 18px;
}
/* Container สำหรับรูปภาพและข้อความ */
.image-container {
    position: relative;      /* กำหนดให้เป็น relative เพื่อให้สามารถวาง element ข้างในแบบ absolute ได้ */
    text-align: center;      /* จัดให้เนื้อหาอยู่กึ่งกลาง */
    width: 100%;            /* กำหนดความกว้างให้เต็ม container */
    margin-bottom: 2rem;     /* เพิ่มระยะห่างด้านล่าง */
}

/* จัดการรูปภาพ */
.group-banner {
    width: 100%;            /* ให้รูปกว้างเต็ม container */
    height: 300px;          /* กำหนดความสูงคงที่ */
    object-fit: cover;      /* ทำให้รูปปรับขนาดแบบคงสัดส่วนและเต็มพื้นที่ */
    border-radius: 8px;     /* มุมโค้ง */
}

/* ส่วนข้อความที่อยู่บนรูป */
.image-text {
    position: absolute;     /* วางตำแหน่งแบบ absolute เทียบกับ container */
    top: 50%;              /* วางกึ่งกลางแนวตั้ง */
    left: 50%;             /* วางกึ่งกลางแนวนอน */
    transform: translate(-50%, -50%);  /* ปรับให้ตัวข้อความอยู่ตรงกลางพอดี */
      
 
}

.image-text h1 {
    margin: 0;             /* ลบ margin ของ h1 */
    font-size: 2.5rem;     /* ขนาดตัวอักษร */
    font-weight: bold;     /* ตัวหนา */
}

img {

border-radius: 8px;
}

/* Media query สำหรับหน้าจอขนาดเล็ก */
@media (max-width: 768px) {
    .group-banner {
        height: 200px;     /* ลดความสูงลงสำหรับหน้าจอมือถือ */
    }
    
    .image-text h1 {
        font-size: 1.8rem; /* ลดขนาดตัวอักษรสำหรับหน้าจอมือถือ */
    }
}

</style>
@endsection