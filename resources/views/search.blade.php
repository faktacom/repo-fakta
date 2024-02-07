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
<div class="row justify-content-center" style="grid-column-start: span 12">
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
        <h4 class="text-center my-3">Search Result</h4>
        <div class="card w-100">
            <div class="card-header content p-0">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a class="nav-link {{request()->get('tab') == "konten" ? 'active' : 
                        ''}} {{request()->get("tab") == "" ? 'active' : ''}}" href="?q={{$q}}&tab=konten">Konten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{request()->get('tab') == "user" ? 'active' : 
                        ''}}" href="?q={{$q}}&tab=user">User</a>
                    </li>
                </ul>
            </div>

            <div id="draftData" class="pt-3 bg-white container fkt-search-news-container">
                @if (\Request::get('tab') == "konten" || \Request::get('tab') == "")
                @forelse ($search as $news)
                <div class="updates-card">
                    <div class="row">
                        <div class="col-lg-4 col-4">
                            @php
                                if(strpos($news->image, "assets/images/bank_image/") === false){
                                    $news->image = "assets/news/images/".$news->image;
                                }
                            @endphp
                            <div class="image-updates">
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
                                        <h6><b>{!!html_entity_decode($news->title)!!}</b></h6>
                                    </a>
                                    <p>{!!\Str::limit($news->description, 150)!!}</p>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <h6>News with title "{{$q}}" not found</h6>
                @endforelse
                {{$search->appends(['q' => $q])->links('custom-pagination')}}
                @elseif(\Request::get('tab') == "user")
                @forelse ($search as $user)
                <div class="updates-card">
                    <div class="row">
                        <div class="col-lg-4 col-4">
                            <div class="image-updates">
                                @if ($user->profile_picture == NULL)
                                <img src="{{asset('assets/images/profile/no-profile.jpg')}}" class="img-fluid">
                                @else
                                <img src="{{asset('assets/images/profile/'. $user->profile_picture)}}" class="img-fluid">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-8 col-8">
                            <div class="description-updates">
                                <div class="content-description">
                                    <a href="{{route('profile.detail', $user->username)}}" style="text-decoration: none;">
                                        <h6>{{$user->name}}</h6>
                                    </a>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <h6>User with name "{{$q}}" not found</h6>
                @endforelse
                @endif
            </div>
        </div>
    </div>
</div>
@endsection