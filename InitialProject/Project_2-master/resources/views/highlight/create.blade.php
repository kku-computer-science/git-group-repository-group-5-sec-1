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
        <div class="card" style="padding: 16px;">
            <div class="card-body">
                <div onclick="window.location.href = '{{ route('all-highlight.index') }}'">
                    <i class="menu-icon mdi mdi-arrow-left" title="back to manage"
                        style="font-size: 24px; cursor: pointer;"></i>
                </div>
                <h4 class="card-title" style="text-align: center;">สร้างไฮไลท์</h4>

            </div>
        </div>
    </div>
@stop
@section('javascript')
    <script></script>
@endsection
