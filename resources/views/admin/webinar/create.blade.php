@extends('layouts.admin')

@section('title', 'Create Webinar')


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
                <li class="breadcrumb-item"><a href="{{route('admin.webinar.index')}}">Webinar</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <form action="{{route('admin.webinar.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mb-2">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control form-control-sm"
                                placeholder="Enter title" value="{{old('title')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Address</label>
                            <textarea name="address" id="" rows="5" class="form-control form-control-sm">{{old('address')}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Schedule</label>
                            <input type="datetime-local" name="schedule" class="form-control form-control-sm" value="{{old('schedule')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Link</label>
                            <input type="text" name="link" class="form-control form-control-sm"
                                placeholder="Enter link" value="{{old('link')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Biaya</label>
                            <input type="number" name="biaya" class="form-control form-control-sm"
                                placeholder="Enter biaya" value="{{old('biaya')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Description</label>
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
                            <div class="card-header" data-toggle="collapse" data-target="#collapseCategories"
                                aria-expanded="false" aria-controls="collapseCategories">
                                Kompartemen <i class="icon-arrow-down"></i>
                            </div>
                            <div class="collapse multi-collapse" id="collapseCategories">
                                <div class="card-body">
                                    <select class="form-control large js-select2" style="width:100%" name="category_id">
                                        @foreach ($list_category as $cat)
                                        @if (old('category_id') == $cat->category_id)
                                        <option value="{{$cat->category_id}}" selected>{{$cat->title}}</option>
                                        @else
                                        <option value="{{$cat->category_id}}">{{$cat->title}}</option>                               
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseTags"
                                aria-expanded="false" aria-controls="collapseTags">
                                Tags <i class="icon-arrow-down"></i>
                            </div>
                            <div class="collapse multi-collapse" id="collapseTags">
                                <div class="card-body">
                                    <select class="form-control js-example-basic-multiple" name="tag_id[]" multiple="multiple" style="width:100%" name="tags">
                                        @foreach ($list_tag as $tag)
                                        <option value="{{$tag->tag_id}}">{{$tag->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseOrganizer"
                                aria-expanded="false" aria-controls="collapseOrganizer">
                                <div class="form-group">
                                    <label for="">Organizer</label>
                                    <input type="text" name="organizer" class="form-control form-control-sm"
                                        placeholder="Enter organizer" value="{{old('organizer')}}">
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseModerator"
                                aria-expanded="false" aria-controls="collapseModerator">
                                <div class="form-group">
                                    <label for="">Moderator</label>
                                    <input type="text" name="moderator" class="form-control form-control-sm"
                                        placeholder="Enter moderator" value="{{old('moderator')}}">
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseSpeaker1"
                                aria-expanded="false" aria-controls="collapseSpeaker1">
                                <div class="form-group">
                                    <label for="">Speaker 1</label>
                                    <input type="text" name="speaker_1" class="form-control form-control-sm"
                                        placeholder="Enter speaker 1" value="{{old('speaker_1')}}">
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseSpeaker2"
                                aria-expanded="false" aria-controls="collapseSpeaker2">
                                <div class="form-group">
                                    <label for="">Speaker 2</label>
                                    <input type="text" name="speaker_2" class="form-control form-control-sm"
                                        placeholder="Enter speaker 2" value="{{old('speaker_2')}}">
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapsePriority"
                                aria-expanded="false" aria-controls="collapsePriority">
                                <div class="form-group">
                                    <label for="">Priority</label>
                                    <input type="number" name="priority" class="form-control form-control-sm"
                                        placeholder="Enter priority" value="{{old('priority')}}">
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