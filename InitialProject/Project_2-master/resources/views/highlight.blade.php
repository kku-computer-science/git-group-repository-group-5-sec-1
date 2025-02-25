<!-- filepath: /e:/Software_en/Se_project/git-group-repository-group-5-sec-1/InitialProject/Project_2-master/resources/views/highlight.blade.php -->
@extends('layouts.layout')
<style>
.count {
    background-color: #f5f5f5;
    padding: 20px 0;
    border-radius: 5px;


}

.count-title {
    font-size: 40px;
    font-weight: normal;
    margin-top: 10px;
    margin-bottom: 0;
    text-align: center;
}

.count-text {
    font-size: 15px;
    font-weight: normal;
    margin-top: 10px;
    margin-bottom: 0;
    text-align: center;

}

.fa-2x {
    margin: 0 auto;
    float: none;
    display: table;
    color: #4ad1e5;
}

.card-title {
    color:#19568A;
    font-family: 'Kanit', sans-serif;
    font-size: 16px;
}
</style>

@section('content')
    <div class="container">
        <h2 class="text-center">Highlight Banner</h2>

        @if ($highlights->isEmpty())
            <p class="text-center">No highlights available.</p>
        @else
            <div class="row">
                @foreach ($highlights as $highlight)
                    <div class="col-md-4 mb-4"> 
                        <div class="card" style="cursor: pointer;" onclick="window.location.href = '{{ route('highlight.details', $highlight->id) }}'">
                            <img src="{{ url('/highlight-image/' . $highlight->banner) }}" 
                                class="card-img-top rounded img-fluid" 
                                alt="{{ $highlight->topic }}">
                            <div class="card-body text-center">
                                <h5>{{ Str::limit($highlight->topic, 50) }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection


