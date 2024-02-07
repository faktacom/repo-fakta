@if ($trending->isEmpty())
    <div style="height: 150px;">
        <h6 style="text-align: center;">Data tidak Ditemukan!</h6>
    </div>
@else
    @foreach ($trending as $trend)
    <a href="{{route('news.detail', ['user' => $trend->username, 'slug' => $trend->slug])}}">
        <div class="row">
            <div class="col-lg-8 col-8">
                <div class="title">
                    <h5>{{$trend->title}}</h5>
                </div>
                <div class="profile">
                    <img src="{{asset('assets/img/profile.png')}}">
                    <span>Korporat</span>
                </div>
            </div>
            <div class="col-lg-4 col-4">
                <img src="{{asset('assets/news/images/'.$trend->featured_image)}}" class="img-fluid">
            </div>

            <div class="col-lg-12 mt-2">
                <div class="info">
                    <span><i class="fa fa-user"></i> {{$trend->name}}</span>
                    <span><i class="fa fa-calendar"></i> {{date('d F Y', strtotime($trend->created_date))}}</span>
                </div>
            </div>
        </div>
    </a>
    @endforeach
@endif
