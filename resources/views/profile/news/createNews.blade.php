@extends('layouts.front')

@section('title', 'Tambah Berita')

@push('css')
{{-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'> --}}
<link rel="stylesheet" href="{{asset('assets/css/jquery.toast.css')}}">
@endpush


@push('js')

<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('content', {
        filebrowserUploadUrl: "{{route('profile.ckeditor.upload', ['_token' => csrf_token() ])}}",
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

{{-- <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js'></script> --}}

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
<script src="{{asset('assets/js/jquery.toast.min.js')}}"></script>
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
<section class="create-content my-4">
    <div class="card">
        <form action="{{route('profile.createNewsStore')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="">Featured Image</label>
                            <input type="file" name="featured_image" class="form-control form-control-sm"
                                onchange="readURLFeatured(this);">
                            <div class="mt-2">
                                <img src="" id="previewFeatured" class="img-fluid" width="150px">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Image</label>
                            <input type="file" name="image" class="form-control form-control-sm"
                                onchange="readURL(this);">
                            <div class="mt-2">
                                <img src="" id="preview" class="img-fluid" width="150px">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="Title..." value="{{old('title', request()->title)}}">
                        </div>
                        <div class="form-group">
                            <label for="">Content</label>
                            <textarea class="ckeditor" name="content">{!! old('content', request()->content) !!}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Category</label>
                            <select class="form-control large js-select2" style="width:100%" name="category_id">
                                @foreach ($category as $cat)
                                <option value="{{$cat->id}}" {{$cat->id == old('category_id', request()->category_id) ? 'selected' : ''}}>{{$cat->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Tags</label>
                            <select class="form-control js-example-basic-multiple" name="tags[]" multiple="multiple"
                                style="width
                                :100%" name="tags">
                                @foreach ($tag as $item)
                                <option value="{{$item->id}}" 
                                
                               >{{$item->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-4 ml-auto">
                        <div class="row">
                            <div class="col-lg-6 col-6">
                                <input class="btn btn-secondary btn-block" style="font-size:14px;" name="save" type="submit"
                                    value="Draft">
                            </div>
                            <div class="col-lg-6 col-6">
                                <input type="submit" name="save" value="Simpan" class="btn btn-korporat btn-block">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
