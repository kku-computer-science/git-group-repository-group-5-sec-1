
@extends('layouts.layout')
@section('content')
<div class="container mt-5">
    <!-- ข้อมูลกลุ่ม -->
    
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
            @endif
        </div>
    </div>

    <!-- หัวข้อวิจัยที่เป็นจุดเน้นของกลุ่ม -->
    <div class="card mb-4">
        <div class="card-body">
            <h2>Main research areas/topics </h2>
            @if($resgd->group_desc_en)
                <ul>
                    @foreach(explode("\n", $resgd->group_desc_en) as $topic)
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
            <h2>Members of Research Group</h2>
            
            <!-- หัวหน้ากลุ่มวิจัย -->
            <h3>Head of Reseach Group</h3>
            @foreach($resgd->user as $user)
                @if($user->pivot->role == 5)
                    <p>{{ $user->academic_ranks_en }} {{ $user->fname_en }} {{ $user->lname_en }} {{ $user->department_name_en }}</p>
                @endif
            @endforeach

            <!-- สมาชิก -->
            <h3>Members</h3>
            <ul>
                @foreach($resgd->user as $user)
                    @if($user->pivot->role == 2)
                        <li>{{ $user->academic_ranks_en }} {{ $user->fname_en }} {{ $user->lname_en }} {{ $user->department_name_en }}</li>
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
                @if($user->pivot->role == 5)
                    <p>{{ $user->academic_ranks_en }} {{ $user->fname_en }} {{ $user->lname_en }}, {{ $user->email }}</p>
                    @if($user->picture)
                        <img src="{{ asset($user->picture) }}" alt="Contact Person" style="max-width: 300px;">
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