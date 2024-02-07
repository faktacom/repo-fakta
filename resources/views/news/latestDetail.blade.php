@extends('layouts.front', ['listAds' => $listAds])
@section('title', 'Home Page')

@push('js')
<script>
    $(document).ready(function () {
        var smallSlide = $("#krp-headline-news-small");
        var bigSlide = $("#krp-headline-news-big");
        var syncedSecondary = true;
        if (window.innerWidth > 768) {
            smallSlide
                .owlCarousel({
                    items: 2,
                    slideSpeed: 2000,
                    nav: false,
                    autoplay: true,
                    dots: false,
                    margin: 10,
                    loop: true,
                    autoHeight: true,
                    responsiveRefreshRate: 200,
                })
                .on("changed.owl.carousel", syncPosition);

            bigSlide
                .on("initialized.owl.carousel", function () {
                    bigSlide
                        .find(".owl-item")
                        .eq(0)
                        .addClass("current");
                })
                .owlCarousel({
                    items: 1,
                    dots: false,
                    nav: true,
                    navText: [
                        '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        '<i class="fa fa-chevron-right" aria-hidden="true"></i>'
                    ],
                    smartSpeed: 200,
                    slideSpeed: 500,
                    slideBy: 5,
                    autoHeight: true,
                    responsiveRefreshRate: 100
                })
                .on("changed.owl.carousel", syncPosition2);

            function syncPosition(el) {
                var count = el.item.count - 1;
                var current = Math.round(el.item.index - el.item.count / 2 - 0.5);

                if (current < 0) {
                    current = count;
                }
                if (current > count) {
                    current = 0;
                }
                bigSlide
                    .find(".owl-item")
                    .removeClass("current")
                    .eq(current)
                    .addClass("current");
                var onscreen = bigSlide.find(".owl-item.active").length - 1;
                var start = bigSlide
                    .find(".owl-item.active")
                    .first()
                    .index();
                var end = bigSlide
                    .find(".owl-item.active")
                    .last()
                    .index();

                if (current > end) {
                    bigSlide.data("owl.carousel").to(current, 100, true);
                }
                if (current < start) {
                    bigSlide.data("owl.carousel").to(current - onscreen, 100, true);
                }
            }

            function syncPosition2(el) {
                if (syncedSecondary) {
                    var number = el.item.index;
                    smallSlide.data("owl.carousel").to(number, 100, true);
                }
            }
        } else {
            bigSlide
                .on("initialized.owl.carousel", function () {
                    bigSlide
                        .find(".owl-item")
                        .eq(0)
                        .addClass("current");
                })
                .owlCarousel({
                    items: 1,
                    dots: false,
                    autoplay: true,
                    loop: true,
                    autoplayTimeout:3000,
                    autoplayHoverPause:true,
                    nav: true,
                    navText: [
                        '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        '<i class="fa fa-chevron-right" aria-hidden="true"></i>'
                    ],
                    smartSpeed: 200,
                    slideSpeed: 500,
                    autoHeight: true,
                });
        }
    });

    $('#krp-market-data-slide').owlCarousel({
            autoplay: true,
            dots: false,
            loop: true,
            autoplayTimeout:3000,
            autoplayHoverPause:true,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 3
                },
                1024: {
                    items: 3
                },
                1366: {
                    items: 4
                }
            }
        });

    // function loadMoreData(page) {
    //     $.ajax({
    //         url: '?page=' + page,
    //         type: 'get',
    //         timeout: 5000
    //         beforeSend: function () {
    //             $(".ajax-load").show();
    //         }
    //     }).done(function (data) {
    //         if (data.html == "") {
    //             $('.ajax-load').html("<h6 class='text-center more'>Data tidak Ditemukan!</h6>");
    //             return;
    //         }
    //         $('.ajax-load').hide();
    //         $('#newsData').append(data.html);
    //     }).fail(function (jqHXR, ajaxOptions, thrownError) {
    //         alert('Server not responding...');
    //     });
    // }

    // var page = 1;
    // $(window).scroll(function () {
    //     if (window.innerWidth > 768) {
    //         if ($(window).scrollTop() + $(window).height() + 1 >= $(document).height()) {
    //             page++;
    //             loadMoreData(page);
    //         }
    //     } else {
    //         if ($(window).scrollTop() + $(window).height() + 70 >= $(document).height()) {
    //             page++;
    //             loadMoreData(page);
    //         }
    //     }
    // });
</script>
@endpush
@push('css')
@endpush

@section('content')

<section class="krp-market-data-section">
    <div class="krp-market-data-title">
        <em style="font-style: normal;">MARKET DATA</em>
    </div>
    <div class="owl-carousel owl-theme" id="krp-market-data-slide">
        <div class="krp-market-data-item">
            <span style="background-color: var(--color-green);">GOLD</span>
            <div class="krp-market-data-content">
                <div>IHSG</div>
                <div>IHSG</div>
                <div>IHSG</div>
            </div>
        </div>
        <div class="krp-market-data-item">
            <span style="background-color: var(--color-blue);">FOREX</span>
            <div class="krp-market-data-content">
                <div>IHSG</div>
                <div>IHSG</div>
                <div>IHSG</div>
            </div>
        </div>
    </div>
</section>

<section class="krp-headline-section">
    <div class="krp-headline-slider">
        <div class="krp-headline-news">
            @if (isset($listLatestNews) && $listLatestNews->count() > 0)
            <div class="owl-carousel owl-theme" id="krp-headline-news-big">
                @foreach ($listLatestNews as $slider)
                <div class="item">
                    <a href="{{route('news.detail', ['user' => $slider->username, 'slug' => $slider->slug])}}">
                        <div class="image">
                            <img src="{{asset('assets/news/images/' . $slider->image)}}" class="img-fluid">
                        </div>
                        <div class="krp-home-headline-text">
                            <h3>HEADLINE</h3>
                        </div>
                        <div class="overlay">
                            <div class="caption">
                                <h6>{{$slider->title}}</h6>
                                <div class="profile">
                                    <img src="../assets/img/profile.png">
                                    <span>Korporat</span>
                                </div>
                                <div class="info">
                                    <span><i class="fa fa-user"></i> {{$slider->name}}</span>
                                    <span><i class="fa fa-calendar"></i>
                                        {{date('d/m/Y H:i', strtotime($slider->created_date))}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="slider-not-found">
                <h6>Data tidak ditemukan</h6>
            </div>
            @endif

            @if (isset($listLatestNews) && $listLatestNews->count() > 0)
            <div class="owl-carousel owl-theme" id="krp-headline-news-small">
                @foreach ($listLatestNews as $slider)
                <div class="item">
                    <a href="{{route('news.detail', ['user' => $slider->username, 'slug' => $slider->slug])}}">
                        <div class="image">
                            <img src="{{asset('assets/news/images/' . $slider->image)}}" class="img-fluid">
                        </div>
                        <div class="overlay">
                            <div class="caption">
                                <h6>{{$slider->title}}</h6>
                                <div class="profile">
                                    <img src="../assets/img/profile.png">
                                    <span>Korporat</span>
                                </div>
                                <div class="info">
                                    <span><i class="fa fa-user"></i> {{$slider->name}}</span>
                                    <span><i class="fa fa-calendar"></i>
                                        {{date('d/m/Y H:i', strtotime($slider->created_date))}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="slider-not-found">
                <h6>Data tidak ditemukan</h6>
            </div>
            @endif
        </div>
        <div class="krp-trending-news">
            <div class="krp-trending-title">
                <em style="font-style: normal;">LATEST</em>
            </div>
            @if (isset($listLatestNews) && $listLatestNews->count() > 0)
            <div class="krp-trending-news-container">
                @foreach ($listLatestNews as $trend)
                <a href="{{route('news.detail', ['user' => $trend->username, 'slug' => $trend->slug])}}">
                    <div class="krp-trending-news-item">
                        <div class="krp-trending-news-title">
                            <h5>{{$trend->title}}</h5>
                            <div class="krp-trending-news-author">
                                <span><i class="fa fa-user"></i> {{$trend->name}}</span>
                                <span><i class="fa fa-calendar"></i>{{date('d/m/Y H:i',
                                    strtotime($trend->created_date))}}</span>
                            </div>
                        </div>
                        <div class="krp-trending-news-image"
                            style="background-image: url({{asset('assets/news/images/'.$trend->featured_image)}})">
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="slider-not-found">
                <h6>Data tidak ditemukan</h6>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection