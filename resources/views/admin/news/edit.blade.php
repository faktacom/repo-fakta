@extends('layouts.admin')

@section('title', 'News Edit')


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
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <form action="{{route('admin.news.update', $detail_news->news_id)}}" method="post" enctype="multipart/form-data" id="formEdit">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mb-2">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" id="title" class="form-control form-control-sm" placeholder="Enter title" value="{{$detail_news->title}}">
                        </div>
                        <div class="form-group">
                            <label for="">Caption</label>
                            <input type="text" name="caption" id="caption" class="form-control form-control-sm"
                                placeholder="Enter caption" value="{{$detail_news->caption}}">
                        </div>
                        <div class="form-group">
                            <label>News Type</label>
                            <select class="form-control form-control-sm" style="width:100%" name="news_type_id" id="typeOption">
                                @foreach ($list_news_type as $news_type)
                                <option value="{{$news_type->news_type_id}}" {{$news_type->news_type_id ==
                                    $detail_news->news_type_id || $news_type->news_type_id == old('news_type_id') ? 'selected' : ''}} >{{$news_type->news_type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Link</label>
                            <input type="text" name="link_video" id="linkVideo" class="form-control form-control-sm" placeholder="Enter link video" value="{{!empty(old('link_video')) ? old('link_video') : $detail_news->link_video}}">
                        </div>
                        <div class="form-group">
                            <label for="">Show Date</label>
                            @if (!empty(old('show_date')))
                                <input type="date" name="show_date" id="showDate" class="form-control form-control-sm" value="{{old('show_date')}}">
                            @elseif (isset($detail_news->show_date))
                                <input type="date" name="show_date" id="showDate" class="form-control form-control-sm" value="{{ date('Y-m-d', strtotime($detail_news->show_date))}}">    
                            @else
                                <input type="date" name="show_date" id="showDate" class="form-control form-control-sm">
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="">Show time</label>
                            @if (!empty(old('show_time')))
                                <input type="time" name="show_time" id="showTime" class="form-control form-control-sm" value="{{old('show_time')}}">
                            @elseif (isset($detail_news->show_date))
                                <input type="time" name="show_time" id="showTime" class="form-control form-control-sm" value="{{ date('H:i', strtotime($detail_news->show_date))}}">    
                            @else
                                <input type="time" name="show_time" id="showTime" class="form-control form-control-sm">
                            @endif
                        </div>
                        @php
                            function isJsonString($value) {
                                $decoded = json_decode($value);
                                return $decoded !== null && json_last_error() === JSON_ERROR_NONE;
                            }
                        @endphp

                        <input type="hidden" name="content_autosave" id="contentAutosave" value="{{htmlspecialchars($detail_news->content)}}">
                        @if (isJsonString($detail_news->content))
                            <div class="form-group">
                                <label for="">Content</label>
                                <div id="editorjs" style="min-height: 500px; background-color:rgba(0,0,0,.03);" data-image_upload="{{route('admin.editorjs.upload', ['_token' => csrf_token() ])}}" data-link_upload="{{route('admin.editorjs.link', ['_token' => csrf_token() ])}}"></div>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea class="ckeditor" name="content" id="ckEditor">{!! $detail_news->content !!}</textarea>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapseAutoSave"
                                aria-expanded="false" aria-controls="collapseAutoSave">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="autosave" id="autoSaveToggle" checked>
                                    <span class="form-check-label">Autosave</span>
                                </label>
                            </div>
                        </div>
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
                                            <img src="{{asset('assets/news/images/' . $detail_news->featured_image)}}"
                                                id="previewFeatured" class="img-fluid" alt="" onerror="replaceMissingImage(this,'{{$detail_news->featured_image}}')">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#multiCollapseExample1"
                                aria-expanded="false" aria-controls="multiCollapseExample1">
                                Image <i class="icon-arrow-down"></i>
                            </div>
                            <div class="collapse multi-collapse" id="multiCollapseExample1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                            <button type="button" class="btn btn-info btn-sm" onclick="showBankImage('image')"> Browse Image</button>
                                            <input type="hidden" name="image_id" id="image">
                                            {{-- <input type='file' name="image" id="image" onchange="readURL(this);" /> --}}
                                        </div>
                                        <div class="col-lg-12">
                                            <img src="{{asset('assets/news/images/'.$detail_news->image)}}" id="preview"
                                                class="img-fluid" alt="" onerror="replaceMissingImage(this,'{{$detail_news->image}}')">
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
                                            <textarea name="description" id="description" class="form-control" rows="4">{{$detail_news->description}}</textarea>
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
                                        <option value="{{$cat->category_id}}" {{$cat->category_id ==
                                            $detail_news->category_id ? 'selected' : ''}} >{{$cat->title}}</option>
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

                                        <option value="{{$tag->tag_id}}" @foreach ($tag_news as $itemTags)
                                            @if(in_array($tag->tag_id, old('tags', [$itemTags->tag_id])))
                                            selected="selected"
                                            @endif
                                            @endforeach>
                                            {{$tag->title}}
                                        </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                        </div>
                        <div class="card mb-3">
                            <div class="card-header" data-toggle="collapse" data-target="#collapsePremiumContent"
                                aria-expanded="false" aria-controls="collapsePremiumContent">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="premium_content" id="premiumContent" {{$detail_news->is_premium || old('premium_content') ? 'checked' : ''}}>
                                    <span class="form-check-label">Premium Content</span>
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="save" id="save" value="">
                    </div>
                </div>
            </div>
            @if (!isJsonString($detail_news->content))
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm d-block ml-auto " id="editData">Update</button>
                </div>
            @endif
        </form>
            @if (isJsonString($detail_news->content))
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm d-block ml-auto " id="editData">Update</button>
                </div>
            @endif
    </div>
</div>
@endsection

@push('js')
<script type="module" src=" {{ asset('assets/js/editor.js') }}"></script>
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script>
    var ckEditor = document.getElementById('ckEditor');
    if(ckEditor){
        CKEDITOR.replace('content', {
        filebrowserUploadUrl: "{{route('admin.ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    }

    var listBankImageDefault = {!! json_encode($list_bank_image) !!};
    var detailNews = {!! json_encode($detail_news) !!};
    var isEdit = true;

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

<script>
    function autoSave(){
        var formData = new FormData();
        var title = document.getElementById('title').value;
        var caption = document.getElementById('caption').value;
        var newsTypeId = document.getElementById('typeOption').value;
        var linkVideo= document.getElementById('linkVideo').value;
        var showDate = document.getElementById('showDate').value;
        var showTime = document.getElementById('showTime').value;
        var featuredImage = document.getElementById('featuredImage').value;
        var image = document.getElementById('image').value;
        var description = document.getElementById('description').value;
        var categoryId = document.getElementById('categoryId').value;
        var tagsOptions = document.getElementById('tags');
        var tags = [];
            for(var i = 0; i < tagsOptions.selectedOptions.length; i++){
            tags.push(tagsOptions.selectedOptions[i].value);
        }
        var premiumContent = document.getElementById('premiumContent').checked;
        var content = document.getElementById('contentAutosave').value;
        var url = document.getElementById('formEdit').action;

        formData.append('title', title);
        formData.append('caption', caption);
        formData.append('news_type_id', newsTypeId);
        formData.append('link_video', linkVideo);
        formData.append('show_date', showDate);
        formData.append('show_time', showTime);
        formData.append('content', content.replace(/&quot;/g,'"'));
        formData.append('featured_image', featuredImage);
        formData.append('image', image);
        formData.append('description', description);
        formData.append('category_id', categoryId);
        tags.forEach(tag =>{
            formData.append('tags[]', tag);
        });
        formData.append('premium_content', premiumContent);
        formData.append('is_axios', true);
        $.ajax({
            url: url,
            method: 'POST',
            processData: false, // Tambahkan ini untuk mencegah jQuery memproses FormData
            contentType: false, // Tambahkan ini untuk mencegah jQuery mengatur tipe konten
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data:formData,
            success:function(result){
                if(result.valid === true){
                    $.toast({
                        heading: 'Autosaving....',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                }else{
                    $.toast({
                        heading: 'Autosave failed',
                        position: 'top-right',
                        loaderBg: '#de4a58',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                }
                
            },
            error:function(error){
                console.log(error);
            }
        });
    }

    function myFunction() {
        alert("Test123");
    }

    var intervalIsRunning = false;
    var autoSaveToggle = document.getElementById("autoSaveToggle");
    if (autoSaveToggle.checked) {
        startInterval();
    }

    autoSaveToggle.addEventListener("change", function() {
        if (this.checked) {
            startInterval();
        } else {
            stopInterval();
        }
    });

    function startInterval() {
        if (!intervalIsRunning) {
            intervalIsRunning = true;
            interval = setInterval(autoSave, 300000);
        }
    }

    function stopInterval() {
        if (intervalIsRunning) {
            clearInterval(interval);
            intervalIsRunning = false;
        }
    }
</script>

@endpush