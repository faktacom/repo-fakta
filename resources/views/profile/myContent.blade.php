@extends('layouts.front')

@section('title', 'My Content')

@push('js')
<script src="{{asset('assets/js/jquery.toast.min.js')}}"></script>
    @if (Session::has('success'))
<script>
    $(function () {
        $.toast({
            heading: '{{session('success')}}',
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'success',
            hideAfter: 3500,
            stack: 6
        });
    });

</script>
@endif
@endpush

@push('css')
<link rel="stylesheet" href="{{asset('assets/css/jquery.toast.css')}}">

<style>
    .content .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: black;
        background-color: transparent;
        border-bottom: 3px solid #b81f24
    }
    .content .nav-pills .nav-link {
        border-radius: 0;
        font-size: 13px;
        color: black;
    }

</style>
@endpush


@section('content')
<div class="row justify-content-center" id="fkt-user-container">
    <div class="col-lg-8 p-1">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <h4 class="text-center my-3">My Content</h4>
        <div class="card w-100">
            <div class="card-header content p-0">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a class="nav-link {{request()->get('tab') == "draft" ? 'active' : 
                        ''}} {{request()->get("tab") == "" ? 'active' : ''}}" href="?tab=draft">Draft</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->get('tab') == "pending" ? 'active' : 
                        ''}}" href="?tab=pending">Pending</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->get('tab') == "tayang" ? 'active' : 
                        ''}}" href="?tab=tayang">Tayang</a>
                    </li>
                </ul>
            </div>

            @if (\Request::get('tab') == "draft")
            <div id="draftData" class="pt-3 bg-white container">
                @include('profile.myContent.draft')
            </div>

            <div class="ajax-load text-center" style="display:none">
                <p>Load More Post...</p>
            </div>
            @elseif(\Request::get('tab') == "pending")
            <div id="pendingData" class="pt-3 bg-white container">
                @include('profile.myContent.pending')
            </div>

            <div class="ajax-load text-center" style="display:none">
                <p>Load More Post...</p>
            </div>
            @elseif(\Request::get('tab') == "tayang")
            <div id="tayangData" class="pt-3 bg-white container">
                @include('profile.myContent.tayang')
            </div>

            <div class="ajax-load text-center" style="display:none">
                <p>Load More Post...</p>
            </div>
            @elseif(\Request::get('tab') == "")
            <div id="draftData" class="pt-3 bg-white container">
                @include('profile.myContent.draft')
            </div>

            <div class="ajax-load text-center" style="display:none">
                <p>Load More Post...</p>
            </div>

            @endif

        </div>
    </div>
</div>
    @endsection
