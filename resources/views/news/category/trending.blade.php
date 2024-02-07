@extends('layouts.front', ['listAds' => $listAds])

@section('title', 'Trending')

@push('css')
<style>
    nav.scroll-menu {
        overflow: auto;
        white-space: nowrap;
        display: block;
        padding: 0;
    }

    nav.scroll-menu a {
        display: inline-block;
    }

    .nav-pills .nav-link {
        color: black;
        font-weight: 700;
        border-radius: 0;
        font-size: 14px;
    }

    .nav-pills .nav-link:hover {
        color: black;
        border-bottom: 3px solid #1aa3da;
        transition: .1s ease;
        border-radius: 0;
        font-weight: 700;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: black;
        background-color: transparent;
        border-bottom: 3px solid #1aa3da;
        border-radius: 0;
    }

</style>
@endpush

@push('js')
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
            $('#trendingData').append(data.html);
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
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12 my-4">
        <h1 class="text-center font-weight-bold">Trending</h1>
    </div>
    <div class="col-lg-8 trending">
        <nav class="nav nav-pills scroll-menu card-header">
            <a class="nav-item nav-link {{request()->is('trending') ? 'active' : ''}}" href="{{route('trending.detail')}}">Semua</a>
            @foreach ($category as $cat)
            <a class="nav-item nav-link {{request()->is('trending/'. $cat->slug) ? 'active' : ''}}" href="{{route('trendingCat.detail', $cat->slug)}}">{{$cat->title}}</a>
            @endforeach
        </nav>

        <div class="trending-content mt-1" id="trendingData" style="overflow: unset">
            @include('news.category.dataTrending')
        </div>

        <div class="ajax-load text-center" style="display:none">
            <p>Load More Post...</p>
        </div>
    </div>
</div>
@endsection
