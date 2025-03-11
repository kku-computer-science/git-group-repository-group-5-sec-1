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
        color: #19568A;
        font-family: 'Kanit', sans-serif;
        font-size: 16px;
    }

    .album-thumbnail {
        cursor: pointer;
        transition: transform 0.2s;
    }

    .album-thumbnail:hover {
        transform: scale(1.05);
    }
</style>

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        {{-- Display Banner Image --}}
                        @if (!empty($highlight->banner))
                            <img src="{{ url('/highlight-image/' . $highlight->banner) }}" class="img-fluid rounded mb-3"
                                alt="{{ $highlight->topic }}" style="max-width: 100%; height: auto;">
                        @else
                            <p class="text-muted text-center">No Image Available</p>
                        @endif

                        {{-- Display Tags Below the Image --}}
                        <div class="mb-2 text-start">
                            <small class="text-muted">
                                <strong>Tags:</strong>
                                @forelse($highlight->tags as $tag)
                                    <a href="{{ route('highlights.byTag', $tag->id) }}">
                                        <span class="badge bg-secondary">{{ $tag->name }}</span>
                                    </a>
                                @empty
                                    <span class="text-muted">No Tags</span>
                                @endforelse
                            </small>
                        </div>

                        {{-- Display Topic in Large Header Size --}}
                        <h2 class="mt-3 text-center">{{ $highlight->topic ?? 'No Topic Available' }}</h2>

                        {{-- Display Detail with Original Formatting & Left Alignment --}}
                        <div class="text-start mt-2" style="white-space: pre-line;">
                            {{ $highlight->detail ?? 'No Details Available' }}
                        </div>

                        {{-- Display Albums as Thumbnails with Popup --}}
                        <div class="text-start mt-3">
                            <strong>Album:</strong>
                            @if ($highlight->albums->isNotEmpty())
                                <div class="d-flex gap-2 mt-2">
                                    @foreach ($highlight->albums as $album)
                                        <img src="{{ url('/highlight-image/' . $album->url) }}"
                                            class="rounded album-thumbnail"
                                            style="width: 80px; height: 80px; object-fit: cover;"
                                            alt="Album Image"
                                            onclick="showImage('{{ url('/highlight-image/' . $album->url) }}')"
                                            data-bs-toggle="modal"
                                            data-bs-target="#imageModal">
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

    {{-- Modal สำหรับแสดงภาพใหญ่ --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="Preview" style="max-height: 70vh;">
                </div>
            </div>
        </div>
    </div>

    <script>
        function showImage(imageUrl) {
            console.log('Received URL:', imageUrl);
            const modalImage = document.getElementById('modalImage');
            if (modalImage) {
                modalImage.src = imageUrl;
                console.log('Modal Image SRC set to:', modalImage.src);
            } else {
                console.error('Modal image element not found');
            }
        }
    </script>
@endsection
