@extends('layouts.front')
@section('title', $list_news->title)
@push('css')
{{--
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" /> --}}
<link rel="stylesheet" href="{{asset('assets/css/jquery.toast.css')}}">
<style>
    .btn-facebook:hover,
    .btn-twitter:hover,
    .btn-whatsapp:hover,
    .btn-clipboard:hover {
        color: white;
    }

    .btn-facebook {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #3b5998;
        color: white;
        font-size: 1.25rem;
    }

    .btn-twitter {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #00acee;
        color: white;
        font-size: 1.25rem;
    }

    .btn-whatsapp {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #25d366;
        color: white;
        font-size: 1.5rem;
    }

    .btn-clipboard {
        background-color: gray;
        color: white;
    }

    .btn-rounded {
        border-radius: 50%;
        height: 40px;
        width: 40px;
    }
</style>
@endpush

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('welcome') }}" style="color: var(--color-red)">Home</a></li>
    <li><b><a href="{{ route('category.detail', $list_news->category->slug) }}">{{$list_news->category->title}}</a></b></li>
    <li>{{ $list_news->title }}</li>
</ul>
<section class="fkt-leftside-container">
    <div class="krp-detail-news-desc">
        <div class="krp-detail-news-title">
            @if ($list_news->category->slug == "opini")
            <div class="fkt-opini-title-container">
                <div class="fkt-opini-title-img"  style="background-image: url({{asset('assets/images/profile/'. $list_news->user->profile_picture)}});"></div>
                <div class="fkt-opini-title-description-container">
                    <div>
                        Oleh
                        <b>{{$list_news->user->name}}</b>
                    </div>
                    {{date('d F Y H:i', strtotime($list_news->show_date))}} WIB
                    <div class="fkt-opini-title-text">
                        {!! html_entity_decode($list_news->title) !!}
                    </div>
                </div>
            </div>
            @else
                <div class="fkt-detail-news-title-container">
                    <h1> {!! html_entity_decode($list_news->title) !!}</h1> 
                </div>
                <div class="fkt-author-container">
                    <div class="fkt-author-img" style="background-image: url({{asset('assets/images/profile/'. $list_news->user->profile_picture)}});"></div> 
                    <div class="fkt-author-description">
                        <div>
                            Oleh
                            <b>{{$list_news->user->name}}</b>
                        </div>
                    {{date('d F Y H:i', strtotime($list_news->created_date))}} WIB
                    </div>
                </div>
            @endif
        </div>
        <div class="krp-detail-news-banner">
            @if ($list_news->news_type_id == 1)
                @php
                    if(strpos($list_news->featured_image, "assets/images/bank_image/") === false){
                        $list_news->featured_image = "assets/news/images/".$list_news->featured_image;
                    }
                @endphp
                <img src="{{asset($list_news->featured_image)}}" class="img-fluid">
            @else
                <iframe src="{{$list_news->link_video}}" frameborder="0" style="width: 100%; height:300px;"></iframe>
            @endif
            <small>{{ $list_news->caption }}</small>
        </div>
        <div class="fkt-detail-news-container">
            <div class="krp-detail-news-content">
                @php
                function isJsonString($value) {
                    $decoded = json_decode($value);
                    return $decoded !== null && json_last_error() === JSON_ERROR_NONE;
                }
                @endphp
                @if (isJsonString($list_news->content))
                    @php
                        $content = json_decode($list_news->content);
                    @endphp
                    @foreach ($content->blocks as $block)
                        @if ($block->type === 'header')
                            <h{{ $block->data->level }}>{!! $block->data->text !!}</h{{ $block->data->level }}>
                        @elseif ($block->type === 'paragraph')
                            @php
                                $alignment = "left";
                                if (isset($block->data->alignment)) {
                                    $alignment = $block->data->alignment;
                                }
                            @endphp
                            <p style="text-align:{{$alignment}}">{!! $block->data->text !!}</p>
                        @elseif ($block->type === 'list')
                            @if ($block->data->style === 'ordered')
                                <ol>
                            @else
                                <ul>
                            @endif
                            @foreach ($block->data->items as $item)
                                <li>{!! $item !!}</li>
                            @endforeach
                            @if ($block->data->style === 'ordered')
                                </ol>
                            @else
                                </ul>
                            @endif
                        @elseif ($block->type === 'linktool')
                            <a href="{{ $block->data->link }}" target="_blank">{{ $block->data->link }}</a>
                            @elseif ($block->type === 'table')
                            <table>
                                <tbody>
                                    @foreach ($block->data->content as $row)
                                        <tr>
                                            @foreach ($row as $cell)
                                                <td>{!! $cell !!}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif ($block->type === 'quote')
                            <blockquote>{!! $block->data->text !!}</blockquote>
                        @elseif ($block->type === 'image')
                            <img src="{{ $block->data->file->url }}" alt="{{ $block->data->caption }}">
                            <small>{{ $block->data->caption }}</small>
                        @elseif ($block->type === 'embed')
                            @php
                            $height = 0;
                            if(!isset($block->data->height)){
                                $height = 500;
                            } else{
                                $height = $block->data->height;
                            }
                            @endphp
                            <iframe src="{{ $block->data->source }}" frameborder="0" style="height: {{$height}}px; width:100%;"></iframe>
                            <small>{{ $block->data->caption }}</small>
                        @elseif ($block->type === 'raw')
                            {!! $block->data->html !!}
                        @endif
                    @endforeach
                @else
                    {!! $list_news->content !!}
                @endif
            </div>
            <div class="fkt-detail-news-banner">
                @if (isset($listAds['ads7']))
                    <img src="{{asset('assets/images/ads/'.$listAds['ads7']->ads_image_path)}}" onclick="getLinkAds({{ $listAds['ads7']->ads_id }})">
                @else
                <div class="fkt-detail-news-banner-placeholder">
                    <div class="ads-not-found detail-news">Place your ads here</div>
                </div>
                @endif
            </div>
        </div>

        <div class="fkt-author-socmed-container">
            <h4>Bagikan: </h4>
            <a href="#"><img src="{{asset('assets/images/icon/facebook2.png')}}" alt=""></a>                    
            <a href="#" alt=""><img src="{{asset('assets/images/icon/twitter2.png')}}" alt=""></a>
            <a href="#"><img src="{{asset('assets/images/icon/linkedin.png')}}" alt=""></a>
            <a href="#"><img src="{{asset('assets/images/icon/whatsapp.png')}}" alt=""></a>
        </div>
        <div class="fkt-detail-news-tag-container">
            <div>
                @if (isset($listTags) && $listTags->count() > 0)
                @foreach ($listTags as $item)
                <a href="#">
                    <span class="krp-span-tag">{{$item->tag_title}}</span>
                </a>
                @endforeach
                @else
                -
                @endif
            </div>
        </div>
    </div>
    <div class="fkt-popular-videos-container">
        <h2><b class="fkt-section-title">Popular Videos</b></h2>
        <div class="fkt-popular-videos-slider">
            @if (isset($listVideo) && $listVideo->count() > 0)
            @php $number = 0; @endphp
            @foreach ($listVideo as $item)
            <div class="fkt-popular-videos-item">
                <x-embed url="{{ $listVideo[$number]->link_video }}" />
                <a href="#" class="fkt-white-links"> <b>{{$item->title}}</b></a>
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
    <section class="krp-detail-news-comments-section">
        <div class="krp-detail-news-comments-top">
            <h2><b>Komentar ({{$list_news->comment()->count()}})</span> </b></h2>
            <div class="slider-not-found" id="fkt-detail-comment-placeholder">
                <h6><i>Login to comment on this news</i></h6>
            </div>
            <div class="krp-detail-news-comments" id="commentContainer">
                @csrf
            </div>
            @if ($listComment->count() != 0)
            <div class="fkt-detail-news-button-load-more" id="loadMore">
                <button><u>Load More</u></button>
            </div>
            @endif
        </div>

    </section>
    <div class="fkt-berita-terbaru-container">
        <h2><b class="fkt-section-title">Terbaru</b></h2>
        @if (isset($listLatestNews) && $listLatestNews->count() > 0)
            <div class="fkt-berita-terbaru-item-container" id="terbaruContainer">
                @foreach ($listLatestNews as $item)
                    <div class="fkt-berita-terbaru-item">
                        @php
                            if(strpos($item->image, "assets/images/bank_image/") === false){
                                $item->image = "assets/news/images/".$item->image;
                            }
                        @endphp
                        <div class="fkt-news-img news-popular-main" style="background-image: url({{asset($item->image)}});"></div>
                        <div class="fkt-berita-terbaru-item-description-container">
                            <a href="#"> 
                                <div class="fkt-berita-terbaru-item-description-title">
                                    <b>{{$item->title}}</b>
                                </div>
                                <p>{{$item->description}}</p>
                            </a>
                            <Small>{{date('d/m/Y H:i', strtotime($item->created_date))}}</Small>
                        </div>
                    </div>
                @endforeach
                {{$listLatestNews->links('custom-pagination')}}
            </div>
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
                <a href="#">
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
</section>



{{-- <h5><b>RELATED NEWS</b></h5>
<section class="fkt-detail-news-related-section">
    @if (isset($listNewsRelated) && $listNewsRelated->count() > 0)
    @foreach ($listNewsRelated as $item)
    <a href="{{route('news.detail', ['user' => $item->username, 'slug' => $item->slug])}}">
        <div class="fkt-detail-news-related-section card">
            <div class="fkt-detail-news-img news-rekomendasi" style="background-image: url({{asset('assets/news/images/'.$item->featured_image)}});"></div>
            <div class="fkt-detail-news-related-card-content">
                <div class="fkt-detail-news-related-card-text-title">
                    <b>{{$item->title}}</b>
                </div>
                <small>{{$item->name}}</small>
                <small>{{date('d/m/Y H:i', strtotime($item->created_date))}}</small>
            </div>
        </div>
    </a>
    @endforeach
    @else
    <div class="slider-not-found">
        <h6>Data tidak ditemukan</h6>
    </div>
    @endif
</section> --}}

{{-- @if (isset($listNewsRekomendasi) && $listNewsRekomendasi->count() > 0)
<h5><b>REKOMENDASI</b></h5>
<section class="fkt-detail-news-rekomendasi-section">
    @foreach ($listNewsRekomendasi as $item)
    <a href="{{route('news.detail', ['user' => $item->username, 'slug' => $item->slug])}}">
        <div class="fkt-detail-news-rekomendasi-section card">
            <div class="fkt-detail-news-img news-rekomendasi" style="background-image: url({{asset('assets/news/images/'.$item->featured_image)}});"></div>
            <div class="fkt-detail-news-rekomendasi-card-content">
                <div class="fkt-detail-news-rekomendasi-card-text-title">
                    <b>{{$item->title}}</b>
                </div>
                <div>
                    <small>{{$item->name}}</small>
                </div>
                <div>
                    <small>{{date('d/m/Y H:i', strtotime($item->created_date))}}</small>
                </div>
            </div>
        </div>
    </a>
    @endforeach
</section>
@endif --}}
@endsection

@push('js')
<script>
    $(".save-comment").on('click', function() {
        var _komen = $('.comment').val();
        var _post = "{{$list_news->news_id}}";
        var _user = "{{Auth::check() ? Auth::user()->name : 'Guest'}}";
        var _profile = "{{Auth::check() ? Auth::user()->profile_picture : 'no-profile.jpg'}}";

        var vm = $(this);

        $.ajax({
            url: "{{route('addComment.store')}}",
            type: "post",
            dataType: "json",
            data: {
                komen: _komen,
                post: _post,
                _token: "{{csrf_token()}}"
            },
            beforeSend: function() {
                vm.text('Loading...').addClass('disabled');
            },
            success: function(res) {
                var _html = `<div class="krp-detail-news-comments-container animate__animated animate__bounce">
                    <div class="krp-detail-news-comments-image">
                        <img src="{{asset('assets/images/profile/` + _profile + `')}}" class="img-fluid">
                    </div>
                    <div class="krp-detail-news-comments-info">
                        <span>` + _user + `</span>
                        <span>` + _komen + `</span>
                    </div>
                </div>`;

                if (res.bool == true) {
                    $('.krp-detail-news-comments').prepend(_html);
                    $('.comment').val('');
                    $('.no-comments').hide();
                    $('.comment-count').text($('div.krp-profile-komentar').length);
                }

                vm.text('Ditambahkan').removeClass('disabled');
            }
        });
    });
</script>
<script src="{{asset('assets/js/jquery.toast.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $(".btn-clipboard").click(function() {
            var copyText = document.getElementById("linkNews");

            copyText.select();
            copyText.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(copyText.value);

            $.toast({
                icon: 'success',
                heading: 'Success',
                text: 'Link berhasil disalin ke clipboard anda',
                position: 'top-right',
                stack: false
            });
        });
    });

    $('#krp-detail-news-latest-slide').owlCarousel({
        autoplay: true,
        dots: true,
        loop: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                stagePadding: 40,
                items: 1
            },
            768: {
                stagePadding: 0,
                items: 2
            },
            1024: {
                items: 2
            },
            1366: {
                items: 2
            }
        }
    });
    $('#krp-detail-news-infografis-slide').owlCarousel({
        autoplay: true,
        dots: true,
        loop: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
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
                items: 3
            }
        }
    });
    $('#krp-detail-news-trending-slide').owlCarousel({
        autoplay: true,
        dots: true,
        loop: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
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
    const sliderVideo = new Siema({
        selector: '.fkt-popular-videos-slider',
        perPage: {
            0: 1,
            1366: 3,
        },
    });
    const prevVideoBtn = document.querySelector('.fkt-button-slider-prev.video');
    const nextVideoBtn = document.querySelector('.fkt-button-slider-next.video');

    prevVideoBtn.addEventListener('click', () => sliderVideo.prev());
    nextVideoBtn.addEventListener('click', () => sliderVideo.next());
</script>
<script type="text/javascript">
    var commentCard = {
        currentPage: 0,
        drawDOM: function(presetValue = false) {
            var img = presetValue["profile_picture"];
            var html = "";
            html += "<div class='krp-detail-news-comments-container'>";
            html += "<div class='krp-detail-news-comments-image'>";
            html += `<img src="{{asset('assets/images/profile/` + img + `')}}" class='img-fluid'>`;
            html += "</div>";
            html += "<div class='krp-detail-news-comments-info'>";
            html += "<span><b>" + presetValue['username'] + " - " + presetValue['created_date'] + "</b></span>";
            html += "<span><b>" + presetValue['content'] + "</b></span>";
            html += "</div>";
            html += "</div>";
            return html;
        },
        loadMore: function() {
            var newsId = "{{$list_news->news_id}}";
            $.ajax({
                url: "{{route('getMoreComment')}}",
                type: "post",
                dataType: "json",
                data: {
                    page: commentCard.currentPage,
                    newsid: newsId,
                    ajax: true,
                    _token: "{{csrf_token()}}"
                },
                success: function(result) {
                    if (result["data"].length > 0) {
                        var container = document.getElementById("commentContainer");
                        var html = "";
                        for (let i = 0; i < result["data"].length; i++) {
                            html += commentCard.drawDOM(result["data"][i]);
                        }
                        container.insertAdjacentHTML("beforeend", html);
                    } else {
                        loadMoreButton.classList.add('hidden');
                    }
                }
            });
        },
        showEmptyPlaceholder: function() {
            var html = "";
            html += " <h6 class='no-comments'>Belum ada komentar</h6>";
            return html;
        }
    };
</script>
<script>
    var listComment = <?php echo json_encode($listComment); ?>;
    var loadMoreButton = document.getElementById("loadMore");

    if (loadMoreButton) {
        loadMoreButton.addEventListener("click", function() {
            commentCard.currentPage += 3;
            commentCard.loadMore();
        });
    }

    $(document).ready(function() {
        if (listComment.length > 0) {
            commentCard.loadMore();
        } else {
            console.log("test");
            commentCard.showEmptyPlaceholder();
        }
    });
</script>
@endpush