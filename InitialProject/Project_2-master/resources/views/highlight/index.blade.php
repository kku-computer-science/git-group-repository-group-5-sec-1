@extends('dashboards.users.layouts.user-dash-layout')
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
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

                <h4 class="card-title" style="text-align: center;">ไฮไลท์</h4>
                <div class="row">
                    @foreach ($highlights as $highlight)
                        <div class="col-md-4">
                            <img src="{{ url('/highlight-image/' . $highlight->banner) }}"
                                class="rounded img-fluid float-start" alt="Highlight Image">
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    @stop
