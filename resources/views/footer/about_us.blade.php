@extends('layouts.front', ['listAds' => $listAds])

@section('title', $f->name)

@section('content') 
<section class="fkt-content-about-us" style="grid-column-start: span 12">
    <div class="fkt-content-about-us-container">
        <div class="fkt-content-about-us-container description">
            <span>Tentang Kami.</span>
            <div class="fkt-content-about-us-description-container">
                {!! $listAboutUs[0]->content !!}
            </div>
        </div>
        <div class="fkt-about-us-value-container">
            <div class="fkt-about-us-value-description">
               <b> {{ $listAboutUs[0]->value_description }}</b>
            </div>
            @if (isset($listAboutUsValue) && $listAboutUsValue->count() > 0)
                @foreach ($listAboutUsValue as $item)
                <div class="fkt-about-us-value-item">
                    <b>{{$item->title_value}}</b>
                    <p>{{$item->description_value}}</p>
                </div>
                @endforeach
            @else
                <div class="slider-not-found">
                    <h6>Data not found</h6>
                </div>
            @endif
        </div>
        <div class="fkt-content-about-us-container teams">
            <h5><span><B>Our Team</B></span></h5>
            @if (!empty($listAboutUs[0]) && !empty($listAboutUs[0]->team_about_us))
                <div class="fkt-about-us-team-item">
                    {!! $listAboutUs[0]->team_about_us !!}
                </div>      
            @else
                <div class="slider-not-found">
                    <h6>Data not found</h6>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection