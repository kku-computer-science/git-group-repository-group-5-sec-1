@extends('layouts.layout')

<style>
    .tag-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
        /* เพิ่มระยะห่างระหว่าง card */
    }

    .card {
        transition: transform 0.2s;
        /* เพิ่มเอฟเฟกต์เมื่อ hover */
    }

    .card:hover {
        transform: scale(1.05);
        /* ขยาย card เมื่อ hover */
    }

    .topic {
        font-weight: bold;
    }

    .topic:hover {
        color: #108cea;
        text-decoration: underline;
    }

    .tag-link-container {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
</style>

@section('content')
    <div class="container">
        <div class="mt-4 mb-5">
            <div style="font-size: 24px; font-weight: bold;">Highlights ที่เกี่ยวกับ Tag</span>
            <i style="font-size: 20px" class="menu-icon mdi mdi-tag"></i>
            <span style="color: #108cea; font-size: 16px; margin-top: 5px; margin-left: 5px; background-color: #f5f5f5; padding: 5px; border-radius: 5px">{{ $tag->name }}</span>
        </div>

        <div class="tag-row mt-4">
            @forelse($highlights as $highlight)
                <div class="card">
                    <div class="card-body">
                        {{-- Banner Image --}}
                        @if (!empty($highlight->banner))
                            <img src="{{ url('/highlight-image/' . $highlight->banner) }}" class="img-fluid rounded mb-3"
                                alt="{{ $highlight->topic }}" style="max-width: 100%; height: auto;">
                        @else
                            <p class="text-muted text-center">No Image Available</p>
                        @endif

                        {{-- Tags --}}
                        <div class="mb-2 text-start">
                            <small class="text-muted">
                                <strong>Tags:</strong>
                                <div class="tag-link-container">
                                    @forelse($highlight->tags as $tagItem)
                                        <a class="tag-link" href="{{ route('highlights.byTag', $tagItem->id) }}"
                                            style="text-decoration: none;">
                                            <span class="badge bg-secondary">{{ Str::limit($tagItem->name, 40, '...') }}</span>
                                        </a>
                                    @empty
                                        <span class="text-muted">No Tags</span>
                                    @endforelse
                                </div>

                            </small>
                        </div>

                        {{-- Topic --}}
                        <a href="{{ url('/highlight' . $highlight->id) }}" style="text-decoration: none; color: inherit;">
                            <div class="mt-3 text-center topic">{{ $highlight->topic ?? 'No Topic Available' }}</div>
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-center">No highlights found for this tag.</p>
            @endforelse
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
