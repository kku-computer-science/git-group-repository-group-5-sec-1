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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">

                        {{-- ✅ Display Banner Image --}}
                        @if (!empty($highlight->banner))
                            <img src="{{ url('/highlight-image/' . $highlight->banner) }}" 
                                class="img-fluid rounded mb-3" 
                                alt="{{ $highlight->topic }}" 
                                style="max-width: 100%; height: auto;">
                        @else
                            <p class="text-muted text-center">No Image Available</p>
                        @endif

                        {{-- ✅ Display Tags Below the Image --}}
                        <div class="mb-2 text-start">
                            <small class="text-muted">
                                <strong>Tags:</strong>
                                @forelse($highlight->tags as $tag)
                                    <span class="badge bg-secondary">{{ $tag->name }}</span>
                                @empty
                                    <span class="text-muted">No Tags</span>
                                @endforelse
                            </small>
                        </div>

                        {{-- ✅ Display Topic in Large Header Size --}}
                        <h2 class="mt-3 text-center">{{ $highlight->topic ?? 'No Topic Available' }}</h2>

                        {{-- ✅ Display Detail with Original Formatting & Left Alignment --}}
                        <div class="text-start mt-2" style="white-space: pre-line;">
                            {{ $highlight->detail ?? 'No Details Available' }}
                        </div>

                        {{-- ✅ Display Albums as Thumbnails or Show "No Album" Message (Left Aligned) --}}
                        <div class="text-start mt-3">
                            <strong>Album:</strong>
                            @if ($highlight->albums->isNotEmpty())
                                <div class="d-flex gap-2 mt-2">
                                    @foreach($highlight->albums as $album)
                                        <img src="{{ url('/highlight-image/' . $album->url) }}" 
                                            class="rounded" 
                                            style="width: 80px; height: 80px; object-fit: cover;" 
                                            alt="Album Image">
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted ms-2">No Album</span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection