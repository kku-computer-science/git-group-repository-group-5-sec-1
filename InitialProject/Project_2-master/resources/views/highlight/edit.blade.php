@extends('dashboards.users.layouts.user-dash-layout')
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css">
<style type="text/css">
    .dropdown-toggle {
        height: 40px;
        width: 400px !important;
    }

    body label:not(.input-group-text) {
        margin-top: 10px;
    }

    body .my-select {
        background-color: #EFEFEF;
        color: #212529;
        border: 0 none;
        border-radius: 10px;
        padding: 6px 20px;
        width: 100%;
    }

    .highlight-box {
        cursor: pointer;
        border: 2px solid #00000021;
        border-radius: 10px;
        height: 200px;
        display: flex;
        position: relative;
    }

    .highlight-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .highlight-placeholder {
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #0000000a;
        border-radius: 10px;
    }

    .highlight-box:hover .topic {
        display: block;
    }

    .topic {
        display: none;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #000000a1;
        color: white;
        padding: 5px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        text-align: center;
    }

    .mdi-arrow-left:hover {
        color: #2781ff;
    }
</style>
@section('content')
    <div class="container">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card" style="padding: 16px;">
            <div class="card-body">
                <div onclick="window.location.href = '{{ route('all-highlight.index') }}'">
                    <i class="menu-icon mdi mdi-arrow-left" title="back to manage"
                        style="font-size: 24px; cursor: pointer;"></i>
                </div>
                <h4 class="card-title" style="text-align: center;">แก้ไขไฮไลท์</h4>
                <form class="row g-3 mt-3" action="{{ route('all-highlight.update', $highlight->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Banner -->
                    <div id="banner-row-preview" class="form-group row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-8">
                            <img id="bannerPreview" src="{{ url('/highlight-image/' . $highlight->banner) }}"
                                alt="Banner Preview" style="width: 100%; margin-top: 10px; height: auto;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-3"><b>Banner</b></p>
                        <div class="col-sm-8">
                            <input id="bannerImageInput" type="file" name="banner"
                                accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control">
                        </div>
                    </div>
                    <!-- Topic -->
                    <div class="form-group row">
                        <p class="col-sm-3"><b>Topic</b></p>
                        <div class="col-sm-8">
                            <input name="topic" class="form-control" placeholder="หัวข้อไฮไลท์" value="{{ $highlight->topic }}">
                        </div>
                    </div>
                    <!-- Detail -->
                    <div class="form-group row">
                        <p class="col-sm-3"><b>Detail</b></p>
                        <div class="col-sm-8">
                            <textarea name="detail" class="form-control" style="height:400px">{{ $highlight->detail }}</textarea>
                        </div>
                    </div>
                    <!-- Albums -->
                    <div class="form-group row">
                        <p class="col-sm-3 pt-4"><b>Album</b></p>
                        <div class="col-sm-8">
                            <table class="table" id="dynamicAddRemove">
                                <tr>
                                    <th>
                                        <button type="button" name="add" id="add-btn2"
                                            class="btn btn-success btn-sm add"><i class="mdi mdi-plus"></i>
                                        </button>
                                    </th>
                                </tr>
                                @foreach ($highlight->albums as $index => $album)
                                    <tr id="row{{ $index }}">
                                        <td style="padding: 0;">
                                            <div class="input-group mb-3">
                                                <input type="file" name="albums[{{ $index }}][]" class="form-control me-2"
                                                    accept="image/png, image/jpeg, image/jpg, image/webp"
                                                    id="albumInput{{ $index }}" multiple>
                                                <div class="input-group-append">
                                                    <button style="padding: 8px 10px;" type="button" class="btn btn-danger remove-btn" data-id="{{ $index }}">
                                                        <i class="mdi mdi-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="album-preview" id="albumPreviewContainer{{ $index }}"
                                                style="margin-bottom: 10px; display: flex; flex-wrap: wrap; gap: 10px;">
                                                <img src="{{ url('/highlight-image/' . $album->url) }}"
                                                    alt="Album Preview" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-right: 12%; justify-content: flex-end;">
                        <button style="width: auto;" type="submit" class="btn btn-primary mt-4">Update</button>
                        <form action="{{ route('all-highlight.destroy', $highlight->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this highlight?');">
                            @csrf
                            @method('DELETE')
                            <button style="width: auto;" type="submit" class="btn btn-danger mt-4">Delete</button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('javascript')
    <script>
        // Show Banner when uploaded
        document.getElementById("bannerImageInput").addEventListener("change", function(e) {
            const bannerPreview = document.getElementById("bannerPreview");
            const bannerRowPreview = document.getElementById("banner-row-preview");
            if (e.target.files && e.target.files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    bannerPreview.src = e.target.result;
                    bannerPreview.style.display = "block";
                    bannerRowPreview.style.display = "flex";
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Dynamic add/remove album fields with multiple image input and preview
        document.addEventListener("DOMContentLoaded", function() {
            let i = {{ $highlight->albums->count() }}; // Start index after existing albums
            const wrapper = document.getElementById("dynamicAddRemove");

            // Add new album field when clicking the "add" button
            document.getElementById("add-btn2").addEventListener("click", function(e) {
                e.preventDefault();
                const newRow = document.createElement("tr");
                newRow.id = `row${i}`;
                newRow.innerHTML = `
                    <td style="padding: 0;">
                        <div class="input-group mb-3">
                            <input type="file" name="albums[${i}][]" class="form-control me-2"
                                accept="image/png, image/jpeg, image/jpg, image/webp"
                                id="albumInput${i}" multiple>
                            <div class="input-group-append">
                                <button style="padding: 8px 10px;" type="button" class="btn btn-danger remove-btn" data-id="${i}">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="album-preview" id="albumPreviewContainer${i}" style="margin-bottom: 10px; display: flex; flex-wrap: wrap; gap: 10px;">
                        </div>
                    </td>`;
                wrapper.appendChild(newRow);

                // Add event listener for the new file input to show previews
                const albumInput = document.getElementById(`albumInput${i}`);
                albumInput.addEventListener("change", function(e) {
                    const previewContainer = document.getElementById(`albumPreviewContainer${i}`);
                    previewContainer.innerHTML = ""; // Clear previous previews
                    if (e.target.files && e.target.files.length > 0) {
                        Array.from(e.target.files).forEach(file => {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const imgElement = document.createElement("img");
                                imgElement.src = e.target.result;
                                imgElement.alt = "Album Preview";
                                imgElement.style.width = "150px";
                                imgElement.style.height = "150px";
                                imgElement.style.objectFit = "cover";
                                imgElement.style.borderRadius = "10px";
                                previewContainer.appendChild(imgElement);
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                });

                i++; // Increment the counter after adding the row
            });

            // Remove album field when clicking the "remove" button
            wrapper.addEventListener("click", function(e) {
                const removeBtn = e.target.closest(".remove-btn");
                if (removeBtn) {
                    e.preventDefault();
                    const rowId = removeBtn.getAttribute("data-id");
                    const row = document.getElementById(`row${rowId}`);
                    if (row) {
                        row.remove(); // Remove the row from the DOM
                    }
                }
            });

            // Preview existing album uploads
            @foreach ($highlight->albums as $index => $album)
                document.getElementById(`albumInput${{ $index }}`).addEventListener("change", function(e) {
                    const previewContainer = document.getElementById(`albumPreviewContainer${{ $index }}`);
                    previewContainer.innerHTML = ""; // Clear previous previews
                    if (e.target.files && e.target.files.length > 0) {
                        Array.from(e.target.files).forEach(file => {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const imgElement = document.createElement("img");
                                imgElement.src = e.target.result;
                                imgElement.alt = "Album Preview";
                                imgElement.style.width = "150px";
                                imgElement.style.height = "150px";
                                imgElement.style.objectFit = "cover";
                                imgElement.style.borderRadius = "10px";
                                previewContainer.appendChild(imgElement);
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                });
            @endforeach
        });
    </script>
@endsection
