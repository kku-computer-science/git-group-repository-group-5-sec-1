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

    .highlight-placeholder:hover {
        background-color: #00000021;
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
</style>
@section('content')
    <div class="container">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card" style="padding: 16px;">
            <div class="card-body">
                <h4 class="card-title" style="text-align: center;">ไฮไลท์ทั้งหมด</h4>
                <div class="row" style="gap: 10px; margin-left: auto; margin-right: auto;">
                    @if (count($highlights) == 0)
                        <div onclick="window.location.href = '{{ route('all-highlight.create') }}'"
                            class="highlight-placeholder col-md-4" style="padding: 0; width: 32%; height: 200px;">
                            <i class="menu-icon mdi mdi-library-plus" style="font-size: 4vw; opacity: 0.75"></i>
                        </div>
                    @else
                        <div onclick="window.location.href = '{{ route('all-highlight.create') }}'"
                            class="highlight-placeholder col-md-4" style="padding: 0; width: 32%;">
                            <i class="menu-icon mdi mdi-library-plus" style="font-size: 4vw; opacity: 0.75"></i>
                        </div>
                    @endif
                    @foreach ($highlights as $highlight)
                        <div class="col-md-4 highlight-box" style="padding: 0; width: 32%;">
                            <img src="{{ url('/highlight-image/' . $highlight->banner) }}"
                                class="rounded img-fluid float-start" alt="{{ $highlight->topic }}">
                            <div class="topic">
                                {{ Str::limit($highlight->topic, 90) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop
