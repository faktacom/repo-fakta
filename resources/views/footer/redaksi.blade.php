@extends('layouts.front', ['listAds' => $listAds])

@section('title', $f->name)

@section('content')

<section class="krp-content-redaksi" style="grid-column-start: span 4">
    <div class="krp-content-redaksi-title">
        <h1>REDAKSI</h1>
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
</section>
@endsection