@extends('layouts.layout')
@section('content')
<div class="container card-3 ">
    <p>Research Group</p>
    @foreach ($resg as $rg)
<<<<<<< HEAD
    <div class="container">
        <a href="{{ route('researchgroupdetail',['id'=>$rg->id])}}"
            class="btn btn-outline-info">
        <img src="{{asset('img/'.$rg->group_image)}}" alt="Avatar" class="image">
        <div class="overlay">
          <div class="text">{{ $rg->{'group_name_'.app()->getLocale()} }}</div>
          <div>
            
        </div>
        </div>
    </a>
      </div>
    
    @endforeach
</div>
<style>
    .container {
      position: relative;
      width: 50%;
    }
    
    .image {
      display: block;
      width: 100%;
      height: auto;
    }
    
    .overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: #008CBA;
      overflow: hidden;
      width: 100%;
      height: 0;
      transition: .5s ease;
    }
    
    .container:hover .overlay {
      height: 20%;
    }
    
    .text {
      white-space: nowrap; 
      color: white;
      font-size: 20px;
      position: absolute;
      overflow: hidden;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
    }
    </style>
=======
    <div class="card mb-4">
        <div class="row g-0">
            <div class="col-md-4">
                <div class="card-body">
                    <img src="{{asset('img/'.$rg->group_image)}}" alt="...">
                    <h2 class="card-text-1"> Laboratory Supervisor </h2>
                    
                    <h2 class="card-text-2">
                        @foreach ($rg->user as $r)
                        @if($r->hasRole('teacher'))
                        @if(app()->getLocale() == 'en' and $r->academic_ranks_en == 'Lecturer' and $r->doctoral_degree == 'Ph.D.')
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
                </div>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"> {{ $rg->{'group_name_'.app()->getLocale()} }}</>
                    </h5>
                    <h3 class="card-text">{{ Str::limit($rg->{'group_desc_'.app()->getLocale()}, 350) }}
                    </h3>
                </div>
                <div>
                    <a href="{{ route('researchgroupdetail',['id'=>$rg->id])}}"
                        class="btn btn-outline-info">{{ trans('message.details') }}</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

>>>>>>> Nantapong_1341
@stop