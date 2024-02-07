@extends('layouts.front', ['listAds' => $listAds])
@section('title', 'Video')

@push('css')
{{--
<link rel="stylesheet" href="{{asset('assets/css/iframe-lightbox.min.css')}}"> --}}
{{--
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css"> --}}
@endpush

@push('js')
<script>
    $(document).ready(function () {
        var smallSlide = $("#krp-headline-news-small");
        var bigSlide = $("#krp-headline-news-big");
        var syncedSecondary = true;
        if (window.innerWidth > 768) {
            smallSlide
                .owlCarousel({
                    items: 3,
                    slideSpeed: 2000,
                    nav: true,
                    autoplay: true,
                    dots: true,
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
                    dots: true,
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
                })
        }

        $('#krp-multimedia-slide').owlCarousel({
            autoplay: true,
            dots: true,
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

        $('#krp-data-slide').owlCarousel({
            autoplay: true,
            dots: true,
            loop: true,
            autoplayTimeout:3000,
            autoplayHoverPause:true,
            responsive: {
                0: {
                    stagePadding: 40,
                    items: 1
                },
                768: {
                    stagePadding: 0,
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

        $('#krp-energy-slide').owlCarousel({
            autoplay: true,
            dots: true,
            loop: true,
            autoplayTimeout:3000,
            autoplayHoverPause:true,
            responsive: {
                0: {
                    stagePadding: 40,
                    items: 1
                },
                768: {
                    stagePadding: 0,
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
        $('#slider-infografik').owlCarousel({
            items:5,
            loop:true,
            margin:15,
            nav:true,
            autoHeight:true,
            responsive:{
                0:{
                    items:1
                },
                768:{
                    items:2
                },
                1024:{
                    items:3
                },
                1366:{
                    items:5
                }
            }   
        });

        // $.ajax({
        //     url: "https://api.currencyfreaks.com/latest?apikey=a05a5057b15e4a0098e03bc7acc46937&symbols=IDR,EUR,CNY",
        //     method: 'get',
        //     dataType: 'json',
        //     success: function (res) {
        //         $(".loadingkurs").hide();
        //         $('#trkurs').append(`
        //         <table class="table table-striped table-bordered">
        //                 <thead>
        //                     <tr>
        //                         <th>IDR</th>
        //                         <th>EUR</th>
        //                         <th>YUAN</th>
        //                     </tr>
        //                 </thead>
        //                 <tbody>
        //                     <tr>
        //                         <td>` + res.rates["IDR"] + `</td>
        //                         <td>` + res.rates["EUR"] + `</td>
        //                         <td>` + res.rates["CNY"] + `</td>
        //                     </tr>
        //                 </tbody>
        //             </table>
                    
        //     `);

        //     }
        // });

        $.ajax({
        url: "https://fcsapi.com/api-v3/stock/indices_latest?country=Indonesia&access_key=rGhZgMdE2vBDY6DUf9C9YL8",
        method: 'get',
        dataType: 'json',
        success: function (res) {
            $('.loading').hide();
            $('#trIhsg').append(`
                <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>High</th>
                                <th>Low</th>
                                <th>Close</th>
                                <th>Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>` + res.response[4].name + `</td>
                                <td>` + formatRupiah(parseInt(res.response[4].h)) + `</td>
                                <td>` + formatRupiah(parseInt(res.response[4].l)) + `</td>
                                <td>` + formatRupiah(parseInt(res.response[4].c)) + `</td>
                                <td>` + res.response[4].cp + `</td>
                            </tr>
                        </tbody>
                    </table>
            `);

            $('#trLq45').append(`
                <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>High</th>
                                <th>Low</th>
                                <th>Close</th>
                                <th>Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>` + res.response[1].name + `</td>
                                <td>` + formatRupiah(parseInt(res.response[1].h)) + `</td>
                                <td>` + formatRupiah(parseInt(res.response[1].l)) + `</td>
                                <td>` + formatRupiah(parseInt(res.response[1].c)) + `</td>
                                <td>` + res.response[1].cp + `</td>
                            </tr>
                        </tbody>
                    </table>
            `);

            $('#trJii').append(`
                <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>High</th>
                                <th>Low</th>
                                <th>Close</th>
                                <th>Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>` + res.response[7].name + `</td>
                                <td>` + formatRupiah(parseInt(res.response[7].h)) + `</td>
                                <td>` + formatRupiah(parseInt(res.response[7].l)) + `</td>
                                <td>` + formatRupiah(parseInt(res.response[7].c)) + `</td>
                                <td>` + res.response[7].cp + `</td>
                            </tr>
                        </tbody>
                    </table>
            `);
        }
    });

    $.ajax({
        url: "https://fcsapi.com/api-v3/stock/latest?symbol=ANTM&access_key=rGhZgMdE2vBDY6DUf9C9YL8",
        method: 'get',
        dataType: 'json',
        success: function (res) {
            $(".loadingAntm").hide();
            $('#trAntm').append(`
                <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>High</th>
                                <th>Low</th>
                                <th>Close</th>
                                <th>Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>` + res.response[1].s + `</td>
                                <td>` + formatRupiah(parseInt(res.response[1].h)) + `</td>
                                <td>` + formatRupiah(parseInt(res.response[1].l)) + `</td>
                                <td>` + formatRupiah(parseInt(res.response[1].c)) + `</td>
                                <td>` + res.response[1].cp + `</td>
                            </tr>
                        </tbody>
                    </table>
                    
                `);
        }
    });
    });
    function formatRupiah(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

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

    var swiper = new Swiper(".swiperVideo", {
        slidesPerView: 1,
        breakpoints: {
            1280: {
                slidesPerView: 3,
                spaceBetween: 15,
            },
        },
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
        clickable: true,
      },
    });

    const sliderSubUtama = new Siema({
        selector: '.fkt-berita-utama-sub-container',
        perPage: {
            0: 1,
            1024: 3,
            1366: 4,
        },
    });

    const prevBtn = document.querySelector('.fkt-button-slider-prev.berita-utama-sub');
    const nextBtn = document.querySelector('.fkt-button-slider-next.berita-utama-sub');

    prevBtn.addEventListener('click', () => sliderSubUtama.prev());
    nextBtn.addEventListener('click', () => sliderSubUtama.next());
</script>
@endpush

@section('content')
<section class="fkt-berita-utama-container">
    <h2 ><b class="fkt-section-title">Video</b></h2>
    @if (isset($listMainVideo) && $listMainVideo->count() > 0)
    @php $number = 0; @endphp
    @foreach ($listMainVideo as $item)
    <div class="fkt-berita-utama-main-container">
        <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}">
            <x-embed url="{{ $item->link_video }}" />
            <div class="fkt-berita-utama-main-description">
                <h5><b>{{$item->title}}</b></h5>
                <small>{{date('d/m/Y H:i', strtotime($item->show_date))}} | <category>{{$item->category_name}}</category> </small>
                <p>{{$item->description}}</p>
                <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}"><u><b>Read More...</b></u></a>
            </div>
        </a>
    </div>
    @php $number++; @endphp
    @endforeach
    @else
    <div class="slider-not-found">
        <h6>Data not found</h6>
    </div>
    @endif
    <div class="fkt-berita-utama-sub-slider">
        <div class="fkt-berita-utama-sub-container">
            @if (isset($listSubVideo) && $listSubVideo->count() > 0)
            @php $number = 0; @endphp
            @foreach ($listSubVideo as $item)
            <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}">
                <div class="fkt-berita-utama-sub-card">
                    <x-embed url="{{ $item->link_video }}" />
                    <div class="fkt-berita-utama-sub-card-content">
                        <div class="fkt-berita-utama-sub-card-text-title">
                            <b>{{$item->title}}</b>
                        </div>
                        <div>
                            <small>{{date('d/m/Y H:i', strtotime($item->show_date))}} | <category>{{$item->category_name}}</category></small>
                        </div>
                    </div>
                </div>
            </a>
            @php $number++; @endphp
            @endforeach
            @else
            <div class="slider-not-found">
                <h6>Data not found</h6>
            </div>
            @endif
        </div>
        <button class="fkt-button-slider-prev berita-utama-sub"><</button>
        <button class="fkt-button-slider-next berita-utama-sub">></button>
    </div>
</section>
<section class="fkt-leftside-container">
    <div class="fkt-berita-terbaru-container">
        <h2><b class="fkt-section-title">Video Terbaru</b></h2>
        @if (isset($listLatestVideo) && $listLatestVideo->count() > 0)
            @php
                $index = 1;
                $draw = false;
            @endphp
            @foreach ($listLatestVideo as $item)
                <div class="fkt-berita-terbaru-item video">
                    <x-embed url="{{ $item->link_video }}" />
                    <div class="fkt-berita-terbaru-item-description-container">
                        <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}"> 
                            <div class="fkt-berita-terbaru-item-description-title">
                                <b>{{$item->title}}</b>
                            </div>
                        </a>
                        <Small>{{date('d/m/Y H:i', strtotime($item->show_date))}} | <category>{{$item->category_name}}</category></Small>
                    </div>
                </div>
            @if ($index == 5)
                @php
                    $draw = true;
                @endphp
                <div class="fkt-popular-videos-container">
                    <h2><b class="fkt-section-title">Popular Videos</b></h2>
                    <div class="swiper swiperVideo">
                        <div class="swiper-wrapper">
                            @if (isset($listPopularVideo) && $listPopularVideo->count() > 0)
                            @php $number = 0; @endphp
                            @foreach ($listPopularVideo as $item)
                            <div class="swiper-slide">
                                <div class="fkt-popular-videos-item">
                                    <x-embed url="{{ $listPopularVideo[$number]->link_video }}" />
                                    <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}" class="fkt-white-links"> <b>{{$item->title}}</b></a>
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
            @if ($index == $listLatestVideo->count() && $draw == false)
                @php
                    $draw = true;
                @endphp
                <div class="fkt-popular-videos-container">
                    <h2><b class="fkt-section-title">Popular Videos</b></h2>
                    <div class="fkt-popular-videos-slider">
                        @if (isset($listPopularVideo) && $listPopularVideo->count() > 0)
                        @php $number = 0; @endphp
                        @foreach ($listPopularVideo as $item)
                        <div class="fkt-popular-videos-item">
                            <x-embed url="{{ $listPopularVideo[$number]->link_video }}" />
                            <a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}" class="fkt-white-links"> <b>{{$item->title}}</b></a>
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
            @php $index++; @endphp
            @endforeach
        {{$listLatestVideo->links('custom-pagination')}}
        @else
            <div class="slider-not-found">
                <h6>Data not found</h6>
            </div>
        @endif
    </div>
</section>
<section class="fkt-rightside-container">
    <div class="fkt-berita-populer-container">
        <h2 ><b class="fkt-section-title">Terpopuler</b></h2>
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
                        <b>{{$item->title}}</b>
                    </div>
                </a>
                <div class="fkt-berita-populer-item-description-date">
                    {{date('d/m/Y H:i', strtotime($item->show_date))}}  | <category>{{$item->category_name}}</category>
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
    <div class="fkt-home-ads-right-3-container">
        @if (isset($listAds['ads4']))
        <div class="fkt-home-ads-right-3-img" id="adsBody2" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads4']->ads_image_path)}});" onclick="getLinkAds({{ $listAds['ads4']->ads_id }})">
        </div>
        @else
        <div class="fkt-home-ads-right-3-img">
            <div class="ads-not-found">Place your ads here</div>
        </div>
        @endif
    </div>
</section>
@endsection