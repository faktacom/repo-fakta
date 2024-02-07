@extends('layouts.admin')

@section('title', 'Create News')


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
                <li class="breadcrumb-item"><a href="{{route('admin.news.index')}}">News</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <form action="{{route('admin.news.store')}}" method="post" enctype="multipart/form-data" id="formPost">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mb-2">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" id="title" class="form-control form-control-sm"
                                placeholder="Enter title"1 value="{{old('title')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Caption</label>
                            <input type="text" name="caption" id="caption" class="form-control form-control-sm"
                                placeholder="Enter caption" value="{{old('caption')}}">
                        </div>
                        <div class="form-group">
                            <label>News Type</label>
                            <select class="form-control form-control-sm" style="width:100%" name="news_type_id" id="typeOption" onchange="showDiv(this)">
                                <option value="">-- select --</option>
                                @foreach ($list_news_type as $news_type)
                                @if (old('news_type_id') == $news_type->news_type_id)
                                <option value="{{$news_type->news_type_id}}" selected>{{$news_type->news_type_name}}</option>    
                                @endif
                                <option value="{{$news_type->news_type_id}}">{{$news_type->news_type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="inputOption"></div>
                        <div class="form-group">
                            <label for="">Show Date</label>
                            <input type="date" name="show_date" id="showDate" class="form-control form-control-sm" value="{{old('show_date')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Show time</label>
                            <input type="time" name="show_time" id="showTime" class="form-control form-control-sm" value="{{old('show_time')}}">
                        </div>
                        {{-- <div class="form-group">
                            <label for="">Content</label>
                            <textarea class="ckeditor" name="content">{{old('content')}}</textarea>
                        </div> --}}
                        <div class="form-group">
                            <label for="">Content</label>
                            <div id="editorjs" name="content" style="min-height: 500px; background-color:rgba(0,0,0,.03);" data-image_upload="{{route('admin.editorjs.upload', ['_token' => csrf_token() ])}}" data-link_upload="{{route('admin.editorjs.link', ['_token' => csrf_token() ])}}"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#featuredImage2"
                                aria-expanded="false" aria-controls="featuredImage2">
                                Featured Image <i class="icon-arrow-down"></i>
                            </div>
                            <div class="collapse multi-collapse" id="featuredImage2">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                            <button type="button" class="btn btn-info btn-sm" onclick="showBankImage('featured_image')"> Browse Image</button>
                                            <input type="hidden" name="featured_image_id" id="featuredImage">
                                            {{-- <input type='file' name="featured_image" id="featuredImage"
                                                onchange="readURLFeatured(this);" /> --}}
                                        </div>
                                        <div class="col-lg-12">
                                            <img src="#" id="previewFeatured" class="img-fluid" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#multiCollapseExample1"
                                aria-expanded="false" aria-controls="multiCollapseExample1">
                                Thumbnail <i class="icon-arrow-down"></i>
                            </div>
                            <div class="collapse multi-collapse" id="multiCollapseExample1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                            <button type="button" class="btn btn-info btn-sm" onclick="showBankImage('image')"> Browse Image</button>
                                            {{-- <input type='file' name="image" id="image" onchange="readURL(this);" /> --}}
                                            <input type="hidden" name="image_id" id="image">
                                        </div>
                                        <div class="col-lg-12">
                                            <img src="#" id="preview" class="img-fluid" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#descriptionCollapse"
                                aria-expanded="false" aria-controls="descriptionCollapse">
                                Description <i class="icon-arrow-down"></i>
                            </div>
                            <div class="collapse multi-collapse" id="descriptionCollapse">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                            <textarea name="description" id="description" class="form-control" rows="4">{{old('description')}}</textarea>
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
                                    <select class="form-control large js-select2" style="width:100%" name="category_id" id="categoryId">
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
                                    <select class="form-control js-example-basic-multiple" name="tags[]" multiple="multiple" style="width:100%" name="tags" id="tags">
                                        @foreach ($list_tag as $tag)
                                        <option value="{{$tag->tag_id}}">{{$tag->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapsePremiumContent"
                                aria-expanded="false" aria-controls="collapsePremiumContent">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="premium_content" id="premiumContent">
                                    <span class="form-check-label">Premium Content</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm d-block ml-auto " id="saveData">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
{{-- <script src=" {{ asset('assets/js/ckeditor/ckeditor.js') }}"></script> --}}
<script type="module" src=" {{ asset('assets/js/editor.js') }}"></script>
<script>
    // CKEDITOR.replace('content', {
    //     filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
    //     filebrowserUploadMethod: 'form'
    // });

    var listBankImageDefault = {!! json_encode($list_bank_image) !!};
    var isEdit = false;

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

    function showDiv(element) {
        var content = "";
        if (element.value > 1) {
            content += "<div class='form-group'>";
            content += "<label>Link</label>";
            content += "<input type='text' name='link_video' id='linkVideo' class='form-control form-control-sm' placeholder='Enter link'>";
            content += "</div>";
            $("#inputOption").html(content);
        } else {
            $("#inputOption").html("");
        }
    }

</script>
<script src="{{ asset('assets/js/bank-image-window.js') }}"></script>
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