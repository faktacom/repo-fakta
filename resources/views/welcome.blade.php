@extends('layouts.front')
@section('title', 'Mewarnai Indonesia')
@section('content')
<section class="fkt-berita-utama-container">
    @if (isset($listNewsFeedMain) && $listNewsFeedMain->count() > 0)
    @foreach ($listNewsFeedMain as $item)
    <div class="fkt-berita-utama-main-container">
        <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}">
            @php
                if(strpos($item->image, "assets/images/bank_image/") === false){
                    $item->image = "assets/news/images/".$item->image;
                }
            @endphp
            <div class="fkt-news-img" style="background-image: url({{$item->image}});"></div>
            <div class="fkt-berita-utama-main-description">
                @php
                    if(strpos($item->title, '<i>') !== false){
                        if(strpos($item->title, '</i>') === false){
							$item->title = strip_tags($item->title);
							$item->title = html_entity_decode($item->title);
                        }
                    }
                @endphp
                <h4>{!!html_entity_decode($item->title)!!}</h4>
                <Small class="fkt-news-date font-weight-bold"><category>{{$item->category_name}}</category> | {{date('d/m/Y', strtotime($item->show_date))}} </Small>
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
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @if (isset($listNewsFeedSub) && $listNewsFeedSub->count() > 0)
            @foreach ($listNewsFeedSub as $item)
            <div class="swiper-slide">
                <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}">
                    <div class="fkt-berita-utama-sub-card">
                        @php
                            if(strpos($item->image, "assets/images/bank_image/") === false){
                                $item->image = "assets/news/images/".$item->image;
                            }
                        @endphp
                        <div class="fkt-news-img news-feed-sub" style="background-image: url({{$item->image}});"></div>
                        <div class="fkt-berita-utama-sub-card-content">
                            @php
                                if(strpos($item->title, '<i>') !== false){
                                    if(strpos($item->title, '</i>') === false){
                                        $item->title = strip_tags($item->title);
                                        $item->title = html_entity_decode($item->title);
                                    }
                                }
                            @endphp
                            <div class="fkt-berita-utama-sub-card-text-title">
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
        </div>
        <div class="swiper-pagination">
        </div>
    </div>
</section>

<section class="fkt-data-pasar-container">
    <div class="fkt-flash-data-wrapper">
        <div class="fkt-flash-news-container">
            <span>PRICE</span>
            <marquee behavior="" direction="">
                <p class="fkt-data-pasar-content"><b>Angka di bawah ini disajikan secara aktual (realtime) dengan melibatkan pihak ketiga.</b></p>
            </marquee>
        </div>
    </div>
    <div class="fkt-data-pasar-chart-container">
        <div class="fkt-data-pasar-chart-button-container">
            <div class="fkt-data-pasar-chart-button active" onclick="openChart(event,'IDR')">IDX</div>
            <div class="fkt-data-pasar-chart-button" onclick="openChart(event,'USD')">LQ45</div>
            <div class="fkt-data-pasar-chart-button" onclick="openChart(event,'JPY')">USD/IDR</div>
            <div class="fkt-data-pasar-chart-button" onclick="openChart(event,'GBP')">GOLD</div>
        </div>
        <div id="IDR" class="fkt-data-pasar-chart-item active" data-symbol="IDX:COMPOSITE"></div>
        <div id="USD" class="fkt-data-pasar-chart-item" data-symbol="IDX:LQ45"></div>
        <div id="JPY" class="fkt-data-pasar-chart-item" data-symbol="FX_IDC:USDIDR"></div>
        <div id="GBP" class="fkt-data-pasar-chart-item" data-symbol="TVC:GOLD"></div>
    </div>
</section>
<div class="fkt-home-ads-center-container full">
    @if (isset($listAds['ads1']))
        <div class="fkt-home-ads-center-img" id="adsBody2" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads1']->ads_image_path)}});" onclick="getLinkAds({{ $listAds['ads1']->ads_id }})"></div>
        @if (!empty($listAds['ads1']->ads_image_path))
            <div class="fkt-home-ads-center-img mobile" id="adsBody2" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads1']->ads_image_path_mobile)}});" onclick="getLinkAds({{ $listAds['ads1']->ads_id }})"></div>
        @else
            <div class="fkt-home-ads-center-img mobile">
                <div class="ads-not-found">Place your ads here</div>
            </div>
        @endif
    @else
    <div class="fkt-home-ads-center-img">
        <div class="ads-not-found">Place your ads here</div>
    </div>
    @endif
</div>

<div class="fkt-berita-terbaru-container">
    <h2><b class="fkt-section-title">Updates</b></h2>
    @if (isset($listLatestNews) && $listLatestNews->count() > 0)
        @php
            $index = 1;
            $draw = $draw1 = $draw2 = $draw3 = false;
        @endphp
        <div class="fkt-berita-terbaru-item-container" id="terbaruContainer">
            @foreach ($listLatestNews as $item)
                <div class="fkt-berita-terbaru-item terbaru">
                    @php
                        if(strpos($item->image, "assets/images/bank_image/") === false){
                            $item->image = "assets/news/images/".$item->image;
                        }
                    @endphp

                    <div class="fkt-news-img news-popular-main" style="background-image: url({{ asset($item->image) }})"></div>

                    <div class="fkt-berita-terbaru-item-description-container">
                        <a href="{{ route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug]) }}">
                            @php
                                if(strpos($item->title, '<i>') !== false){
                                    if(strpos($item->title, '</i>') === false){
                                        $item->title = strip_tags($item->title);
                                        $item->title = html_entity_decode($item->title);
                                    }
                                }
                            @endphp

                            <div class="fkt-berita-terbaru-item-description-title">
                                <b>{!! html_entity_decode($item->title) !!}</b>
                            </div>

                            <p>{{ $item->description }}</p>
                        </a>

                        <small class="fkt-news-date font-weight-bold">
                            <category>{{ $item->category_name }}</category> | {{ date('d/m/Y', strtotime($item->show_date)) }}
                        </small>
                    </div>
                </div>

                @if ($index == 3 && !$draw)
                    @php $draw = true; @endphp
                    <div class="fkt-interaktif-slider-container-mobile">
                        <h2><b class="fkt-section-title">Interactive</b></h2>
                        <div class="swiper swiperInteraktif">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="fkt-data-slider-item">
                                        <div class="fkt-news-img" style="background-image: url('/assets/images/interaktif.png');">
                                        </div>
                                        <a href="https://interaktif.fakta.com/" class="fkt-data-title" ><b>Membedah Sejarah Uang, Duit, dan Rupiah</b></a>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-pagination">
                            </div>
                        </div>
                    </div>
                    <div class="fkt-home-ads-center-container third">
                    @if (isset($listAds['ads8']))
                        <div class="fkt-home-ads-center-img" id="adsBody2" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads8']->ads_image_path)}});" onclick="getLinkAds({{ $listAds['ads8']->ads_id }})"></div>
                        @if (!empty($listAds['ads8']->ads_image_path))
                            <div class="fkt-home-ads-center-img mobile" id="adsBody2" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads8']->ads_image_path_mobile)}});" onclick="getLinkAds({{ $listAds['ads8']->ads_id }})"></div>
                        @else
                            <div class="fkt-home-ads-center-img mobile">
                            <div class="ads-not-found">Place your ads here</div>
                        </div>
                        @endif
                    @else
                        <div class="fkt-home-ads-center-img">
                            <div class="ads-not-found">Place your ads here</div>
                        </div>
                    @endif
                    </div>

                @endif

                @if ($index == 6 && !$draw1)
                    @php $draw1 = true; @endphp
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
                                        <div class="fkt-data-slider-img" style="background-image: url({{$item->image}});"></div>
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

                @endif

                @if ($index == 9 && !$draw2)
                    @php $draw2 = true; @endphp
                    <div class="fkt-infografis-slider-container">
                        <h2><b class="fkt-section-title">Infographics</b></h2>
                        <div class="swiper swiperInfografis">
                            <div class="swiper-wrapper">
                                @if (isset($sliderInfografik) && $sliderInfografik->count() > 0)
                                    @php $number = 0; @endphp
                                    @foreach ($sliderInfografik as $infografikItem)
                                        <div class="swiper-slide">
                                            <div class="fkt-infografis-slider-item">
                                                @php
                                                    if(strpos($infografikItem->image, "assets/images/bank_image/") === false){
                                                        $infografikItem->image = "assets/news/images/".$infografikItem->image;
                                                    }
                                                @endphp
        
                                                <div class="fkt-infografis-slider-img" style="background-image: url({{ $infografikItem->image }});"></div>
        
                                                @php
                                                    if(strpos($infografikItem->title, '<i>') !== false){
                                                        if(strpos($infografikItem->title, '</i>') === false){
                                                            $infografikItem->title = strip_tags($infografikItem->title);
                                                            $infografikItem->title = html_entity_decode($infografikItem->title);
                                                        }
                                                    }
                                                @endphp
        
                                                <a href="{{ route('news.detail', ['category_slug' => $infografikItem->category_slug, 'slug' => $infografikItem->slug]) }}">
                                                    <b>{!! html_entity_decode($infografikItem->title) !!}</b>
                                                </a>
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
                @endif

                @if ($index == 14 && !$draw3)
                    @php $draw3 = true; @endphp
                    <div class="fkt-webinar-container">
                        <h2><b class="fkt-section-title">Program</b></h2>
                        <div class="swiper swiperProgram">
                            <div class="swiper-wrapper">
                                @if (isset($listWebinar) && $listWebinar->count() > 0)
                                    @php $number = 0; @endphp
                                    @foreach ($listWebinar as $webinarItem)
                                        <div class="swiper-slide">
                                            <div class="fkt-webinar-item">
                                                <div class="fkt-webinar-item-img" style="background-image: url({{ asset('assets/images/webinar/'.$webinarItem->featured_image) }});"></div>
        
                                                @php
                                                    if(strpos($webinarItem->title, '<i>') !== false){
                                                        if(strpos($webinarItem->title, '</i>') === false){
                                                            $webinarItem->title = strip_tags($webinarItem->title);
                                                            $webinarItem->title = html_entity_decode($webinarItem->title);
                                                        }
                                                    }
                                                @endphp
        
                                                <a href="{{ route('webinar.detail', ['slug' => $webinarItem->slug]) }}" class="fkt-white-links">
                                                    <b>{!! html_entity_decode($webinarItem->title) !!}</b>
                                                </a>
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
                        <a href="{{ route('webinar') }}" class="fkt-white-links right" style="font-size: 1rem">See All</a>
                    </div>
                @endif

                @php $index++; @endphp
            @endforeach

            <div class="fkt-detail-news-button-load-more">
                <button onclick="loadMore('{{$listLatestNews->nextPageUrl()}}')"><u>INDEKS</u></button>
            </div>
        </div>
    @else
        <div class="slider-not-found">
            <h6>Data not found</h6>
        </div>
    @endif
</div>  
<div class="fkt-berita-populer-container mobile" >
    <h2 ><b class="fkt-section-title">Popular</b></h2>
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
                    <b>{!!html_entity_decode($item->title)!!} </b>
                </div>
            </a>
            <div class="fkt-berita-populer-item-description-date">
                <category>{{$item->category_name}}</category>
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
</div>
<div class="fkt-home-rightside-container">
    <div class ="fkt-glass-up-rightside">
    <div class="fkt-berita-populer-container" >
        <h2 ><b class="fkt-section-title">popular</b></h2>
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
    </div>
    </div>
    <div class="fkt-glass-down-rightside">
        <div class="fkt-glass-in-rightside">
        <div class="fkt-home-ads-right-1-container">
        @if (isset($listAds['ads2']))
        <div class="fkt-home-ads-right-1-img" id="adsBody2" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads2']->ads_image_path)}}); background-repeat: no-repeat;" onclick="getLinkAds({{ $listAds['ads2']->ads_id }})">
        </div>
        @else
        <div class="fkt-home-ads-right-1-img">
            <div class="ads-not-found">Place your ads here</div>
        </div>
        @endif
    </div>
    <div class="fkt-interaktif-container-desktop">
        <h2 ><b class="fkt-section-title">Interactive</b></h2>
        <div class="fkt-data-item">
            <div class="fkt-news-img" style="background-image: url('/assets/images/interaktif.png');">
            </div>
            <a href="https://interaktif.fakta.com/" class="fkt-data-title" style="padding: 20px;"><h3><b>Membedah Sejarah Uang, Duit, dan Rupiah</b></h3></a>
        </div>
    </div>
    </div>
    </div>
</div>



@endsection

@push('js')
<script>
    function openChart(evt, rateName) {
        var i, x, tablinks;
        x = document.getElementsByClassName("fkt-data-pasar-chart-item");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("fkt-data-pasar-chart-button");
        for (i = 0; i < x.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(rateName).style.display = "flex";
        evt.currentTarget.className += " active";
    }

    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        breakpoints: {
            1024: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            1280: {
                slidesPerView: 4,
                spaceBetween: 15,
            }
        },
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
        clickable: true,
      },
    });
    
    var swiper = new Swiper(".swiperInteraktif", {
        slidesPerView: 1,
        breakpoints: {
            768: {
                slidesPerView: 1,
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

    var swiper = new Swiper(".swiperInfografis", {
        slidesPerView: 1,
        breakpoints: {
            768: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            1024: {
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

    var swiper = new Swiper(".swiperProgram", {
        slidesPerView: 1,
        breakpoints: {
            768: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            1280: {
                slidesPerView: 2,
                spaceBetween: 20,
            }
        },
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
        clickable: true,
      },
    });

    
</script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js" integrity="sha512-jNDtFf7qgU0eH/+Z42FG4fw3w7DM/9zbgNPe3wfJlCylVDTT3IgKW5r92Vy9IHa6U50vyMz5gRByIu4YIXFtaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
<!--<script> -->
<!--    $(document).ready(function(){-->
<!--        $item->image.lazyload();-->
<!--    });-->
<!--</script>-->
<!--<script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-tickers.js" async></script>-->
<script type="text/javascript">
    function createChartItem(id, description, proName) {
        const container = document.getElementById(id);
        const script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "https://s3.tradingview.com/external-embedding/embed-widget-tickers.js";
        script.async = true;

        const symbols = [
            {
                "description": description,
                "proName": proName
            }
        ];

        const config = {
            "symbols": symbols,
            "isTransparent": false,
            "showSymbolLogo": false,
            "colorTheme": "light",
            "locale": "id"
        };

        script.innerHTML = JSON.stringify(config);

        container.appendChild(script);
    }

    // Initialize charts
    createChartItem("IDR", "IDX Composite", "IDX:COMPOSITE");
    createChartItem("USD", "LQ45", "IDX:LQ45");
    createChartItem("JPY", "USD/IDR", "FX_IDC:USDIDR");
    createChartItem("GBP", "GOLD", "TVC:GOLD");
</script>
@endpush