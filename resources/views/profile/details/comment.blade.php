@foreach ($komentar as $item)
<div class="updates-card">
    <div class="row">
        <div class="col-lg-4 col-4">
            <div class="image-updates">
                <img src="{{asset('assets/news/images/'. $item->image)}}" class="img-fluid">
            </div>
        </div>
        <div class="col-lg-8 col-8">
            <div class="description-updates">
                <div class="content-description">
                    <a href="{{route('news.detail', ['user' => $item->username, 'slug' => $item->slug])}}"
                        style="text-decoration: none;">
                        <h6>{{$item->title}}</h6>
                    </a>
                    <span style="font-size: 12px"> Komentar: </span>
                    <p>{!! $item->content !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
{{$komentar->withPath('/'.$userName.'?tab=komentar')->links('pagination::bootstrap-4')}}

