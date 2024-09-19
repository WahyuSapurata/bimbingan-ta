@extends('layouts.layout')
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fs-1 text-center text-capitalize">Selamat Datang {{ auth()->user()->name }}</div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
@endsection
