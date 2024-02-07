@extends('layouts.front')

@section('title', 'Profile '. $user->name)

@push('css')
<style>
    .card .cover-photo {
        position: relative;
        /* height: 350px; */
        height: 283px;
        background-size: 100% 100%;
        background-repeat: no-repeat no-repeat;
    }

    .photo {
        display: block;
        border-radius: 50%;
        margin: 0 auto 10px;
        border: 5px solid white;
        width: 120px;
        height: 120px;
        max-width: 120px;
        max-height: 120px;
    }

    .btn-auth {
        position: relative;
        overflow: hidden;
    }

    .btn-auth input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        cursor: inherit;
        display: block;
    }

    .btn-profile {
        color: white;
        background-color: #b81f24;
        padding: 5px 20px;
        font-size: 13px;
    }

    .btn-profile:hover {
        color: white;
    }

    .file-cover {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .content .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: black;
        background-color: transparent;
        border-bottom: 3px solid #b81f24;
    }

    .content .nav-pills .nav-link {
        border-radius: 0;
        font-size: 13px;
        color: black;
    }

</style>
@endpush

@push('js')
@if (\Request::get('tab') == "konten")
<script>
    function loadMoreData(page) {
        $.ajax({
            url: '?tab=konten&page=' + page,
            type: 'get',
            timeout: 5000
            beforeSend: function () {
                $(".ajax-load").show();
            }
        }).done(function (data) {
            if (data.html == "") {
                $('.ajax-load').html("Data tidak ditemukan");
                return;
            }
            $('.ajax-load').hide();
            $('#newsData').append(data.html);
        }).fail(function (jqHXR, ajaxOptions, thrownError) {
            alert('Server not responding...');
        });
    }

    var page = 1;
    $(window).scroll(function () {
        if (window.innerWidth > 992) {
            if ($(window).scrollTop() + $(window).height() + 1 >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        } else {
            if ($(window).scrollTop() + $(window).height() + 70 >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        }
    });

</script>

@elseif(\Request::get('tab') == "komentar")
<script>
    function loadMoreData(page) {
        $.ajax({
            url: '?tab=komentar&page=' + page,
            type: 'get',
            timeout: 5000
            beforeSend: function () {
                $(".ajax-load").show();
            }
        }).done(function (data) {
            if (data.html == "") {
                $('.ajax-load').html("Data tidak ditemukan");
                return;
            }
            $('.ajax-load').hide();
            $('#commentData').append(data.html);
            // console.log(data);
        }).fail(function (jqHXR, ajaxOptions, thrownError) {
            alert('Server not responding...');
        });
    }

    var page = 1;
    $(window).scroll(function () {
        if (window.innerWidth > 992) {
            if ($(window).scrollTop() + $(window).height() + 1 >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        } else {
            if ($(window).scrollTop() + $(window).height() + 70 >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        }
    });

</script>
@elseif(\Request::get('tab') == "")
<script>
    function loadMoreData(page) {
        $.ajax({
            url: '?page=' + page,
            type: 'get',
            timeout: 5000
            beforeSend: function () {
                $(".ajax-load").show();
            }
        }).done(function (data) {
            if (data.html == "") {
                $('.ajax-load').html("Data tidak ditemukan");
                return;
            }
            $('.ajax-load').hide();
            $('#newsData').append(data.html);
        }).fail(function (jqHXR, ajaxOptions, thrownError) {
            alert('Server not responding...');
        });
    }

    var page = 1;
    $(window).scroll(function () {
        if (window.innerWidth > 992) {
            if ($(window).scrollTop() + $(window).height() + 1 >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        } else {
            if ($(window).scrollTop() + $(window).height() + 70 >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        }
    });

</script>
@endif
@endpush

@section('content')
<div class="row justify-content-center" style="grid-column-start: span 12">
    <div class="col-lg-8 p-1">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
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
        <div class="card w-100">
            @if ($user->cover_picture == NULL)
            <div class="cover-photo" style="background-color: gray">
                @else
                <div class="cover-photo"
                    style="background-image: url({{asset('assets/images/profile/' . $user->cover_picture)}})">
                    @endif

                    @if ($user->profile_picture == NULL)
                    <img class="photo" src="{{asset('assets/images/profile/no-profile.jpg')}}" alt="Profile {{$user->name}}">
                    @else
                    <img class="photo" src="{{asset('assets/images/profile/' . $user->profile_picture)}}"
                        alt="Profile {{$user->name}}">
                    @endif
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4 col-7 mx-auto">
                                @guest
                                @else
                                @if(Auth::user())
                                    @if (Auth::user()->id == $user->id)
                                    <a href="{{route('profile.user')}}"
                                        class="btn btn-profile btn-auth text-center d-block">
                                        Settings
                                    </a>
                                    @endif
                                @endif
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>

    
            </div>
            <div class="card-header content p-0">
                <ul class="nav nav-pills nav-fill">

                        <li class="nav-item">
                            <a class="nav-link {{request()->get('tab') == "konten" ? 'active' : 
                            ''}} {{request()->get("tab") == "" ? 'active' : ''}}" href="?tab=konten">Konten</a>
                        </li>

                    <li class="nav-item">
                        <a class="nav-link {{request()->get('tab') == "komentar"  ? 'active' : 
                        ''}}" href="?tab=komentar">Komentar</a>
                    </li>
                </ul>
            </div>

            @if (\Request::get('tab') == "konten")
            <div id="newsData" class="pt-3 bg-white container">
                @include('profile.details.content')
            </div>

            <div class="ajax-load text-center" style="display:none">
                <p>Load More Post...</p>
            </div>
            @elseif(\Request::get('tab') == "komentar" )
            <div id="commentData" class="pt-3 bg-white container">
                @include('profile.details.comment')
            </div>

            <div class="ajax-load text-center" style="display:none">
                <p>Load More Post...</p>
            </div>
            @elseif(\Request::get('tab') == "")
            <div id="newsData" class="pt-3 bg-white container">
                @include('profile.details.content')
            </div>

            <div class="ajax-load text-center" style="display:none">
                <p>Load More Post...</p>
            </div>

            @endif

        </div>
    </div>
    @endsection
