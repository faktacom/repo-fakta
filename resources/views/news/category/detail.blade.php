@extends('layouts.front', ['listAds' => $listAds])
@section('title', 'Mewarnai Indonesia')

@push('js')
<script>
    $(document).ready(function() {
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
                .on("initialized.owl.carousel", function() {
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
                .on("initialized.owl.carousel", function() {
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
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
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
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
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


    
    var swiper = new Swiper(".swiperUtama", {
        slidesPerView: 1,
        breakpoints: {
            1024: {
                slidesPerView: 3,
                spaceBetween: 15,
            },
            1280: {
                slidesPerView: 4,
                spaceBetween: 15,
            },
        },
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
        clickable: true,
      },
    });

    var swiper = new Swiper(".swiperData", {
        slidesPerView: 1,
        breakpoints: {
            768: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            1280: {
                slidesPerView: 3,
                spaceBetween: 10,
            }
        },
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
        clickable: true,
      },
    });
</script>
@endpush
@push('css')
@endpush

@section('content')
<section class="fkt-berita-utama-container">
    <div class="fkt-berita-utama-main-container">
    @if (isset($listNewsFeedCategoryMain) && $listNewsFeedCategoryMain->count() > 0)
    @foreach ($listNewsFeedCategoryMain as $item)
        <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}">
            @php
                if(strpos($item->image, "assets/images/bank_image/") === false){
                    $item->image = "assets/news/images/".$item->image;
                }
            @endphp
            <div class="fkt-news-img" style="background-image: url({{asset($item->image)}});"></div>
            <div class="fkt-berita-utama-main-description">
                @php
                    if(strpos($item->title, '<i>') !== false){
                        if(strpos($item->title, '</i>') === false){
                            $item->title = strip_tags($item->title);
                            $item->title = html_entity_decode($item->title);
                        }
                    }
                @endphp
                <h5><b>{!!html_entity_decode($item->title)!!}</b></h5>
                <small class="fkt-news-date font-weight-bold"><category>{{$item->category_name}}</category> | {{date('d/m/Y', strtotime($item->show_date))}} </small>
                <p>{{$item->description}}</p>
                <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}"><u><b>Read More...</b></u></a>
            </div>
        </a>
    </div>
    @endforeach
    @else
    <div class="slider-not-found">
        <h6>Data not found</h6>
    </div>
    @endif
    <div class="fkt-berita-utama-sub-slider">
        <div class="swiper swiperUtama">
            <div class="swiper-wrapper">
                @if (isset($listNewsFeedCategorySub) && $listNewsFeedCategorySub->count() > 0)
                @foreach ($listNewsFeedCategorySub as $item)
                <div class="swiper-slide">
                    <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}">
                        <div class="fkt-berita-utama-sub-card">
                            @php
                                if(strpos($item->image, "assets/images/bank_image/") === false){
                                    $item->image = "assets/news/images/".$item->image;
                                }
                            @endphp
                            <div class="fkt-news-img news-feed-sub" style="background-image: url({{asset($item->image)}});"></div>
                            <div class="fkt-berita-utama-sub-card-content">
                                <div class="fkt-berita-utama-sub-card-text-title">
                                    @php
                                        if(strpos($item->title, '<i>') !== false){
                                            if(strpos($item->title, '</i>') === false){
                                                $item->title = strip_tags($item->title);
                                                $item->title = html_entity_decode($item->title);
                                            }
                                        }
                                    @endphp
                                    <b>{!!html_entity_decode($item->title)!!}</b>
                                </div>
                                <div>
                                     <Small class="fkt-news-date font-weight-bold"><category>{{$item->category_name}}</category> | {{date('d/m/Y', strtotime($item->show_date))}} </Small>
                                </div>
            
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
                @else
                <div class="slider-not-found">
                    <h6>Data not found</h6>
                </div>
                @endif
                @if (isset($listNewsFeedCategoryChild) && $listNewsFeedCategoryChild->count() > 0)
                @foreach ($listNewsFeedCategoryChild as $item)
                <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}">
                    <div class="fkt-berita-utama-sub-card">
                        @php
                            if(strpos($item->image, "assets/images/bank_image/") === false){
                                $item->image = "assets/news/images/".$item->image;
                            }
                        @endphp
                        <div class="fkt-news-img news-feed-sub" style="background-image: url({{asset($item->image)}});"></div>
                        <div class="fkt-berita-utama-sub-card-content">
                            <div class="fkt-berita-utama-sub-card-text-title">
                                @php
                                    if(strpos($item->title, '<i>') !== false){
                                        if(strpos($item->title, '</i>') === false){
                                            $item->title = strip_tags($item->title);
                                            $item->title = html_entity_decode($item->title);
                                        }
                                    }
                                @endphp
                                <b>{!!html_entity_decode($item->title)!!}</b>
                            </div>
                            <div>
                                 <Small class="fkt-news-date font-weight-bold"><category>{{$item->category_name}}</category> | {{date('d/m/Y', strtotime($item->show_date))}} </Small>
                            </div>
        
                        </div>
                    </div>
                </a>
                @endforeach
                @endif
            </div>
            <div class="swiper-pagination">
            </div>
        </div>
    </div>
    
</section>
    <div class="fkt-berita-terbaru-container category" id="categoryContainer">
        <h2><b class="fkt-section-title">Updates</b></h2>
        @if (isset($listLatestNews) && $listLatestNews->count() > 0)
        @php
            $index = 1;
            $draw = false;
        @endphp
            @foreach ($listLatestNews as $item)
                <div class="fkt-berita-terbaru-item">
                    @php
                        if(strpos($item->image, "assets/images/bank_image/") === false){
                            $item->image = "assets/news/images/".$item->image;
                        }
                    @endphp
                    <div class="fkt-news-img news-popular-main" style="background-image: url({{asset($item->image)}});"></div>
                    <div class="fkt-berita-terbaru-item-description-container">
                        <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}"> 
                            <div class="fkt-berita-terbaru-item-description-title">
                                @php
                                    if(strpos($item->title, '<i>') !== false){
                                        if(strpos($item->title, '</i>') === false){
                                            $item->title = strip_tags($item->title);
                                            $item->title = html_entity_decode($item->title);
                                        }
                                    }
                                @endphp
                                <b>{!!html_entity_decode($item->title)!!}</b>
                            </div>
                            <p>{{$item->description}}</p>
                        </a>
                         <Small class="fkt-news-date font-weight-bold"><category>{{$item->category_name}}</category> | {{date('d/m/Y', strtotime($item->show_date))}} </Small>
                    </div>
                </div>
                @if ($index == 5)
                    @php
                        $draw = true;
                    @endphp
                    <div class="fkt-data-slider-container">
                        <h2><b class="fkt-section-title">Data</b></h2>
                        <div class="swiper swiperData">
                            <div class="swiper-wrapper">
                                @if (isset($listData) && $listData->count() > 0)
                                @php $number = 0; @endphp
                                @foreach ($listData as $item)
                                <div class="swiper-slide">
                                    <div class="fkt-data-slider-item">
                                        @php
                                            if(strpos($item->image, "assets/images/bank_image/") === false){
                                                $item->image = "assets/news/images/".$item->image;
                                            }
                                        @endphp
                                        <div class="fkt-data-slider-img" style="background-image: url({{asset($item->image)}});"></div>
                                        @php
                                            if(strpos($item->title, '<i>') !== false){
                                                if(strpos($item->title, '</i>') === false){
                                                    $item->title = strip_tags($item->title);
                                                    $item->title = html_entity_decode($item->title);
                                                }
                                            }
                                        @endphp
                                        <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}"><b>{!!html_entity_decode($item->title)!!}</b></a>
                                    </div>
                                </div>
                                @php $number++; @endphp
                                @endforeach
                                @else
                                <div class="slider-not-found">
                                    <h6>Data not found</h6>
                                </div>
                                @endif
                            </div>
                            <div class="swiper-pagination">
                            </div>
                        </div>
                    </div>
                    <div class="fkt-home-ads-center-container">
                        @if (isset($listAds['ads1']))
                        <div class="fkt-home-ads-center-img" id="adsBody2" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads1']->ads_image_path)}});" onclick="getLinkAds({{ $listAds['ads1']->ads_id }})">
                        </div>
                        @else
                        <div class="fkt-home-ads-center-img">
                            <div class="ads-not-found">Place your ads here</div>
                        </div>
                        @endif
                    </div>
                @endif
                @if ($index == $listLatestNews->count() && $draw == false)
                @php
                    $draw = true;
                @endphp
                <div class="fkt-popular-videos-container">
                    <h2><b class="fkt-section-title">Videos</b></h2>
                    <div class="fkt-popular-videos-slider">
                        @if (isset($listVideo) && $listVideo->count() > 0)
                        @php $number = 0; @endphp
                        @foreach ($listVideo as $item)
                        <div class="fkt-popular-videos-item">
                            <x-embed url="{{ $listVideo[$number]->link_video }}" />
                            <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}" class="fkt-white-links"> <b>{!!html_entity_decode($item->title)!!}</b></a>
                        </div>
                        @php $number++; @endphp
                        @endforeach
                        @else
                        <div class="slider-not-found">
                            <h6>Data not found</h6>
                        </div>
                        @endif
                    </div>
                    <button class="fkt-button-slider-prev video"><</button>
                    <button class="fkt-button-slider-next video">></button>
                    <a href="{{route('video')}}" class="fkt-white-links right" style="font-size: 1rem">See All</a>
                </div>
                <div class="fkt-home-ads-center-container">
                    @if (isset($listAds['ads1']))
                    <div class="fkt-home-ads-center-img" id="adsBody2" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads1']->ads_image_path)}});" onclick="getLinkAds({{ $listAds['ads1']->ads_id }})">
                    </div>
                    @else
                    <div class="fkt-home-ads-center-img">
                        <div class="ads-not-found">Place your ads here</div>
                    </div>
                    @endif
                </div>
                @endif
                @php
                $index++;
                @endphp
            @endforeach
            {{$listLatestNews->links('custom-pagination')}}
        @else
            <div class="slider-not-found">
                <h6>Data not found</h6>
            </div>
        @endif
    </div>
    <div class="fkt-home-rightside1-container">
    <div class="fkt-berita-populer-container category">
        <h2 ><b class="fkt-section-title">POPULAR</b></h2>
        @php $number = 1; @endphp
        @if (isset($listPopularNews) && $listPopularNews->count() > 0)
        @foreach ($listPopularNews as $item)
        <div class="fkt-berita-populer-item">
            <div class="fkt-berita-populer-item-number">
                <b>#{{$number}}</b>
            </div>
            <div class="fkt-berita-populer-item-description">
                <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}">
                    <div class="fkt-berita-populer-item-description-title">
                        @php
                        if(strpos($item->title, '<i>') !== false){
                            if(strpos($item->title, '</i>') === false){
                                $item->title = strip_tags($item->title);
                                $item->title = html_entity_decode($item->title);
                            }
                        }
                        @endphp
                        <b>{!!html_entity_decode($item->title)!!}</b>
                    </div>
                </a>
                <div class="fkt-berita-populer-item-description-date">
                     <Small class="fkt-news-date font-weight-bold"><category>{{$item->category_name}}</category> | {{date('d/m/Y', strtotime($item->show_date))}} </Small>
                </div>
            </div>
        </div>
        @php $number++; @endphp
        @endforeach
        @else
        <div class="slider-not-found">
            <h6>Data not found</h6>
        </div>
        @endif
        {{-- <div class="fkt-home-ads-right-2-container category">
            @if (isset($listAds['ads3']))
            <div class="fkt-home-ads-right-2-img" id="adsBody2" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads3']->ads_image_path)}});" onclick="getLinkAds({{ $listAds['ads3']->ads_id }})">
            </div>
            @else
            <div class="fkt-home-ads-right-2-img">
                <div class="ads-not-found">Place your ads here</div>
            </div>
            @endif
        </div> --}}
    </div>
    <!--<div class="fkt-home-ads-right-1-container category">
        @if (isset($listAds['ads2']))
        <div class="fkt-home-ads-right-1-img" id="adsBody2" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads2']->ads_image_path)}}); background-repeat: no-repeat;" onclick="getLinkAds({{ $listAds['ads2']->ads_id }})">
        </div>
        @else
        <div class="fkt-home-ads-right-1-img">
            <div class="ads-not-found">Place your ads here</div>
        </div>
        @endif
    </div>-->
        </div>
@endsection