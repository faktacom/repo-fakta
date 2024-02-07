@extends('layouts.front', ['listAds' => $listAds])

@section('title', $f->name)

@section('content')



<section class="fkt-content-pedoman-media-siber" style="grid-column-start: span 4">
    <div class="fkt-content-pedoman-media-siber-title">
        <h1>TERMS OF SERVICE</h1>
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
@endsection