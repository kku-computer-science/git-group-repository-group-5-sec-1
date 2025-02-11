@extends('layouts.layout')
@section('content')
<div class="container card-3 ">
    <p>Research Group</p>
    @foreach ($resg as $rg)
    <a href="{{ route('researchgroupdetail',['id'=>$rg->id])}}"
        class="btn btn-outline-info">
    <div class="container card-3">
        <img src="{{asset('img/'.$rg->group_image)}}" alt="Avatar" class="image">
        <div class="overlay">
          <div class="text">{{ $rg->{'group_name_'.app()->getLocale()} }}</div>
        </div>
    </div>
</a>
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
@stop