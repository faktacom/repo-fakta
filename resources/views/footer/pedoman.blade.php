@extends('layouts.front', ['listAds' => $listAds])

@section('title', $f->name)

@section('content')

@if (isset($listAds['ads5']))
<div class="krp-ads-vertical-left" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads5']->ads_image_path)}}); background-repeat: no-repeat;" onclick="getLinkAds({{ $listAds['ads5']->ads_id }})">
</div>
@else
<div class="krp-ads-vertical-left">
    <div class="ads-not-found">Place your ads here</div>
</div>
@endif

@if (isset($listAds['ads6']))
<div class="krp-ads-vertical-right" style="background-image: url({{asset('assets/images/ads/'.$listAds['ads6']->ads_image_path)}}); background-repeat: no-repeat;" onclick="getLinkAds({{ $listAds['ads6']->ads_id }})">
</div>
@else
<div class="krp-ads-vertical-right">
    <div class="ads-not-found">Place your ads here</div>
</div>
@endif
<section class="fkt-content-pedoman-media-siber">
    <div class="fkt-content-pedoman-media-siber-title">
        <h1>PEDOMAN MEDIA SIBER</h1>
    </div>
    <div class="fkt-content-pedoman-media-siber-container">
        @if (isset($listPedomanMedia) && $listPedomanMedia->count() > 0)
        @foreach ($listPedomanMedia as $item)
            {!! $item->content !!}
        @endforeach
        @else
        <div class="slider-not-found">
            Data tidak ditemukan
        </div>
        @endif
    </div>

</section>

<!-- <section class="krp-content-redaksi">
    <div class="krp-content-redaksi-title">
        <h1>PEDOMAN MEDIA SIBER</h1>
    </div>
    <div class="krp-content-redaksi-container">
        @if (isset($listRedaksi) && $listRedaksi->count() > 0)
        @foreach ($listRedaksi as $item)
        <div class="krp-content-redaksi-detail">
            <div class="krp-content-redaksi-field">
                {{ $item->position }}
            </div>
            <div class="krp-content-redaksi-value">
                <strong>{{ $item->name }}</strong>
            </div>
        </div>
        @endforeach
        @else
        <div class="slider-not-found">
            Data tidak ditemukan
        </div>
        @endif
    </div>
</section> -->
@endsection