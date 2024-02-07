@foreach ($konten as $news)
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
                        <span>{{$news->category->title}}</span>
                    </a>
                </div>
                <div class="content-description">
                    <a href="{{route('news.detail', ['user' => $news->user->username, 'slug' => $news->slug])}}"
                        style="text-decoration: none;">
                        <h6>{{$news->title}}</h6>
                    </a>
                    <p>{!!\Str::limit($news->description, 150)!!}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
{{$konten->withPath('/'.$userName.'?tab=konten')->links('pagination::bootstrap-4')}}

