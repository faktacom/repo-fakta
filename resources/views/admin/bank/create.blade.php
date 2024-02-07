@extends('layouts.admin')

@section('title', 'Upload Image')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">@yield('title')</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.bank.index')}}">Bank Image</a></li>
                <li class="breadcrumb-item active">Upload Image</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <form action="{{route('admin.bank.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mb-2">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="image_title" class="form-control form-control-sm"
                                placeholder="Enter title" value="{{old('image_title')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Caption</label>
                            <input type="text" name="image_caption" class="form-control form-control-sm"
                                placeholder="Enter caption" value="{{old('image_caption')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Image</label>
                            <input type="file" name="image_path" class="form-control form-control-sm" onchange="readURL(this);">
                            <img src="#" id="preview" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm d-block ml-auto ">Upload</button>
            </div>
        </form>
    </div>
</div>
@endsection


@push('js')
<script>
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
</script>
<script src="{{ asset('assets/js/select2.full.js') }}"></script>

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