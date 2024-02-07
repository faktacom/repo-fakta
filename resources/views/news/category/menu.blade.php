@extends('layouts.front', ['listAds' => $listAds])
@section('title', 'Category')

@section('content')
<section class="fkt-category-container">
    <h3>Kategori Berita</h3>
    <div class="fkt-category-item-container">
        @if (isset($listCategory) && $listCategory->count() > 0)
            @foreach ($listCategory as $item)
            <a href="{{route('category.detail', $item->slug)}}">
                <div class="fkt-category-item">
                    <img src="{{ asset('assets/images/category-icon/'.$item->icon_path) }}" alt="" width="40">
                   <h5>{{$item->title}}</h5>
                   <i class="fa fa-arrow-right"></i>
                </div>
            </a>
            @endforeach
        @else
        <div class="slider-not-found">
            <h6>Data not found</h6>
        </div>
        @endif
    </div>
</section>
@endsection