@extends('layouts.admin')

@section('title', 'Create Community')


@push('css')

<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush


@push('js')
<script src=" {{ asset('assets/js/ckeditor/ckeditor.js') }}">
</script>
<script>
    CKEDITOR.replace('content', {
        filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURLFeatured(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#previewFeatured')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>

<script src="{{ asset('assets/js/select2.full.js') }}"></script>

<script>
    $(function () {
        $(".large").select2({
            placeholder: "Select a category",
            allowClear: true
        });
        $('.js-example-basic-multiple').select2({
            placeholder: "Select a tags",
            allowClear: true,
        });
    });
</script>

@if (Session::has('invalid'))
<script>
    $(function () {
        $.toast({
            heading: '{{session('invalid')}}',
            position: 'top-right',
            loaderBg: '#de4a58',
            icon: 'error',
            hideAfter: 3500,
            stack: 6
        });
    });
</script>
@endif

@if ($errors->any())
@foreach ($errors->all() as $error)
<script>
    $(function () {
        $.toast({
            heading: '{{$error}}',
            position: 'top-right',
            loaderBg: '#de4a58',
            icon: 'error',
            hideAfter: 3500,
            stack: 6
        });
    });
</script>
@endforeach
@endif

@endpush

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">@yield('title')</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.community.index')}}">Community</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <form action="{{route('admin.community.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mb-2">
                        <div class="form-group">
                            <label>Community Name</label>
                            <input type="text" name="community_name" class="form-control form-control-sm"
                                placeholder="Enter community name" value="{{old('community_name')}}">
                        </div>
                        <div class="form-group">
                            <label>Kompartemen</label>
                            <select class="form-control form-control-sm" style="width:100%" name="category_id">
                                @foreach ($list_category as $item)
                                <option value="{{$item->category_id}}">{{$item->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="ckeditor" name="description">{{old('description')}}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#featuredImage"
                                aria-expanded="false" aria-controls="featuredImage">
                                Featured Image <i class="icon-arrow-down"></i>
                            </div>
                            <div class="collapse multi-collapse" id="featuredImage">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                            <input type='file' name="featured_image"
                                                onchange="readURLFeatured(this);" />
                                        </div>
                                        <div class="col-lg-12">
                                            <img src="#" id="previewFeatured" class="img-fluid" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseLinkWhatsApp"
                                aria-expanded="false" aria-controls="collapseLinkWhatsApp">
                                <div class="form-group">
                                    <label>Link WhatsApp</label>
                                    <input type="text" name="link_whatsapp" class="form-control form-control-sm"
                                        placeholder="Enter link whatsapp" value="{{old('link_whatsapp')}}">
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseLinkTelegram"
                                aria-expanded="false" aria-controls="collapseLinkTelegram">
                                <div class="form-group">
                                    <label>Link Telegram</label>
                                    <input type="text" name="link_telegram" class="form-control form-control-sm"
                                        placeholder="Enter link telegram" value="{{old('link_telegram')}}">
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseLinkTwitter"
                                aria-expanded="false" aria-controls="collapseLinkTwitter">
                                <div class="form-group">
                                    <label>Link Twitter</label>
                                    <input type="text" name="link_twitter" class="form-control form-control-sm"
                                        placeholder="Enter link twitter" value="{{old('link_twitter')}}">
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseLinkInstagram"
                                aria-expanded="false" aria-controls="collapseLinkInstagram">
                                <div class="form-group">
                                    <label>Link Instagram</label>
                                    <input type="text" name="link_instagram" class="form-control form-control-sm"
                                        placeholder="Enter link instagram" value="{{old('link_instagram')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm d-block ml-auto ">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection