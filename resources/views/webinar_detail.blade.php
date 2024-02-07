@extends('layouts.front')
@section('title', $detailWebinar[0]->title)
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
<section class="fkt-leftside-container">
    <div class="fkt-detail-webinar-container">
        <div class="fkt-detail-webinar-img" style="background-image: url({{asset('assets/images/webinar/'.  $detailWebinar[0]->featured_image)}})">
        </div>
        <div class="fkt-detail-webinar-description">
            <h1>{{$detailWebinar[0]->title}}</h1>
            <div>{!!$detailWebinar[0]->description!!}</div>
        </div>
        <div class="fkt-webinar-person-container">
            <div class="fkt-webinar-pembicara">
                <b>Pembicara</b>
                <span>{{$detailWebinar[0]->speaker_1}}</span>
                <span>{{$detailWebinar[0]->speaker_2}}</span>
            </div>
            <div class="fkt-webinar-moderator">
                <b>Moderator</b>
                <span>{{$detailWebinar[0]->moderator}}</span>
            </div>
        </div>
        <div class="fkt-detail-webinar-date">
            <b>Tanggal : {{date('D, d F Y', strtotime($detailWebinar[0]->schedule))}}</b>
            <b>Jam : {{date('H:i', strtotime($detailWebinar[0]->schedule))}} WIB</b>
            <b>Biaya : {{!empty($detailWebinar[0]->biaya) ? "Rp. ".number_format($detailWebinar[0]->biaya) : "FREE"}}</b>
        </div>
        <button class="fkt-detail-webinar-button-daftar" data-toggle="modal" data-target="#myModal">Daftar</button>
    </div>
    <div class="fkt-popular-videos-container">
        <h2><b class="fkt-section-title">Popular Videos</b></h2>
        <div class="fkt-popular-videos-slider">
            @if (isset($listVideo) && $listVideo->count() > 0)
            @php $number = 0; @endphp
            @foreach ($listVideo as $item)
            <div class="fkt-popular-videos-item">
                <x-embed url="{{ $listVideo[$number]->link_video }}" />
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
                    {{$item->name}}
                </div>
                <div class="fkt-berita-populer-item-description-date">
                    {{date('d/m/Y H:i', strtotime($item->created_date))}}
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
@endsection
@push('js')
<script src="{{asset('assets/js/jquery.toast.min.js')}}"></script>
<script>
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
    function postRegisterWebinar(){
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

        let fd = new FormData();
        let fullName = $('#fullName').val();
        let job = $('#job').val();
        let emailParticipant = $("#emailParticipant").val();
        let noTelepon = $("#noTelepon").val();
        let provinceId = $("#provinceId").val();
        let cityId = $("#cityId").val();
        let webinarId = $("#webinarId").val();
        let webinarSlug = $("#webinarSlug").val();
        let token = "{{csrf_token()}}";
        let count = 0;
        fd.append('webinarId', webinarId);
        fd.append('fullName', fullName);
        fd.append('job', job);
        fd.append('emailParticipant', emailParticipant);
        fd.append('noTelepon', noTelepon);
        fd.append('provinceId', provinceId);
        fd.append('cityId', cityId);
        fd.append('_token', token);

        if (fullName == "") {
            Swal.fire({
                type: 'warning',
                title: 'Oops...',
                text: 'Nama tidak boleh kosong.'
            });
            return false;
        }
        if (fullName == "") {
            Swal.fire({
                type: 'warning',
                title: 'Oops...',
                text: 'Pekerjaan tidak boleh kosong.'
            });
            return false;
        }
        if (emailParticipant != "") {
            if(emailParticipant.match(mailformat)) {
                //
            }else{
                Swal.fire({
                    type: 'warning',
                    title: 'Oops...',
                    text: 'Email tidak valid.'
                });
                return false;
            }
        } else{
            Swal.fire({
                    type: 'warning',
                    title: 'Oops...',
                    text: 'Email tidak boleh kosong.'
            });
            return false;
        }
        if (noTelepon != "") {
            if (isNaN(noTelepon)) {
                Swal.fire({
                    type: 'warning',
                    title: 'Oops...',
                    text: 'Nomor telepon tidak valid'
                });
                return false;
            }
        } else {
            Swal.fire({
                    type: 'warning',
                    title: 'Oops...',
                    text: 'No telepon tidak boleh kosong.'
            });
            return false;
        }
        if (provinceId == "") {
            Swal.fire({
                type: 'warning',
                title: 'Oops...',
                text: 'Asal provinsi tidak boleh kosong.'
            });
            return false;
        }
        if (cityId == "") {
            Swal.fire({
                type: 'warning',
                title: 'Oops...',
                text: 'Asal kota tidak boleh kosong.'
            });
            return false;
        }
        
        $.ajax({
            url: '{{ route("webinar.join") }}',
            type: "POST",
            cache: false,
            processData: false,
            contentType: false,
            data: fd,
            success: function(data) {
                if (data.bool == true) {
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: 'Success Register',
                        text: 'Register berhasil',
                        timer: 1500, 
                        timerProgressBar: true, 
                        showCancelButton: false,
                        showConfirmButton: false
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            location.href = `{{ route('webinar') }}`;
                        }
                    })
                }
                if (data.bool == false) {
                    Swal.fire({
                        type: 'warning',
                        title: 'Already Registered',
                        text: 'Email sudah terdaftar'
                    });
                    return false;
                }
            },
            error: function(xhr, status, error) {
                document.getElementById("coverScreen").style.display = "none";
                var err = eval("(" + xhr.responseText + ")");
                Swal.fire({
                    icon: 'error',
                    title: 'Registrasi Error',
                    text: `${err.Message}`,
                })
            }
        });
    }
</script>
@endpush