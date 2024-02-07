@extends('layouts.front', ['listAds' => $listAds])

@section('title', $f->name)

@section('content')
<section class="krp-content-redaksi" style="grid-column-start: span 12">
    <div class="krp-content-redaksi-title">
    </div>
    <div class="fkt-footer-content-container">
        <div class="fkt-footer-content-title">
            <div class="fkt-footer-content-title-img" style="background-image: url({{ asset('assets/img/logo-fakta.png') }})">

            </div>
            <div class="fkt-footer-content-title-description">
                <h3>{{$f->title}}</h3>
                <small>Uploaded {{date('d F Y ', strtotime($f->created_date))}}</small>
            </div>
        </div>
        <div class="fkt-footer-content">
            {!! $f->content !!}
        </div>
        <a href="{{route('welcome')}}" style="text-align: right"> 
            <span class="fkt-footer-content-button">Mengerti</span>
        </a>
    </div>
</section>
@endsection