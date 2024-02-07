@extends('layouts.front')

@section('title', 'Search Result')

@push('js')
<script src="{{asset('assets/js/jquery.toast.min.js')}}"></script>
@if (Session::has('success'))
<script>
    $(function() {
        $.toast({
            heading: '{{session('
            success ')}}',
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
        border-bottom: 3px solid #b81f24;
    }

    .content .nav-pills .nav-link {
        border-radius: 0;
        font-size: 13px;
        color: black;
    }
</style>
@endpush


@section('content')
<div class="row justify-content-center" style="grid-column-start: span 11">
    <div class="col-lg-8 p-1">
        <h4 class="fkt-tag-title text-center my-3">#{{$tag->title}}</h4>
        <div class="card w-100">
            <div id="draftData" class="pt-3 bg-white container fkt-search-news-container">
                @if (isset($tagNews) && $tagNews->count() > 0)
                    @foreach ($tagNews as $news)
                        <div class="updates-card">
                            <div class="row">
                                <div class="col-lg-4 col-4">
                                    <div class="image-updates">
                                        @php
                                            if(strpos($news->image, "assets/images/bank_image/") === false){
                                                $news->image = "assets/news/images/".$news->image;
                                            }
                                        @endphp
                                        <img src="{{asset($news->image)}}" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-8 col-8">
                                    <div class="description-updates">
                                        <div class="category">
                                            <a href="" style="text-decoration:none;">
                                                <span>{{$news->category_name}}</span>
                                            </a>
                                        </div>
                                        <div class="content-description">
                                            <a href="{{route('news.detail', ['category_slug' => $news->category_slug, 'slug' => $news->slug])}}" style="text-decoration: none;">
                                                @php
                                                    if(strpos($news->title, '<i>') !== false){
                                                        if(strpos($news->title, '</i>') !== true){
                                                            $news->title = strip_tags($news->title);
                                                            $news->title = html_entity_decode($news->title);
                                                        }
                                                    }
                                                @endphp
                                                <h6>{!!html_entity_decode($news->title)!!}</h6>
                                            </a>
                                            <p>{!!\Str::limit($news->description, 150)!!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h6>Data not found</h6>
                @endif
                {{$tagNews->onEachSide(1)->links('pagination::bootstrap-4')}}
            </div>
        </div>
    </div>
</div>
@endsection