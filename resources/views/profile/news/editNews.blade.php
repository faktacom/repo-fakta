@extends('layouts.front')

@section('title', 'Tambah Berita')

@push('css')
{{-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'> --}}
<link rel="stylesheet" href="{{asset('assets/css/jquery.toast.css')}}">
@endpush

@section('content')
<section class="create-content my-4" id="fkt-user-container">
    <div class="card">
        <form action="{{route('profile.updateNews',['id' => $news[0]->news_id] )}}" method="post" enctype="multipart/form-data" id="formEdit">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="">Featured Image</label>
                            <button type="button" class="form-control form-control-sm" onclick="showBankImage('featured_image')"> Browse Image</button>
                            <input type="hidden" name="featured_image_id" id="featuredImage">
                            {{-- <input type="file" name="featured_image" id="featuredImage" class="form-control form-control-sm"
                                onchange="readURLFeatured(this);"> --}}
                            <div class="mt-2">
                                @php
                                if(strpos($news[0]->featured_image, "assets/images/bank_image/") === false){
                                        $news[0]->featured_image = "assets/news/images/".$news[0]->featured_image;
                                    }
                                @endphp
                                <img src="{{asset($news[0]->featured_image)}}" id="previewFeatured" class="img-fluid"
                                    width="150px">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Image</label>
                                <button type="button" class="form-control form-control-sm" onclick="showBankImage('image')"> Browse Image</button>
                                <input type="hidden" name="image_id" id="image">
                            {{-- <input type="file" name="image" id="image" class="form-control form-control-sm"
                                onchange="readURL(this);"> --}}
                            <div class="mt-2">
                                @php
                                if(strpos($news[0]->image, "assets/images/bank_image/") === false){
                                        $news[0]->image = "assets/news/images/".$news[0]->image;
                                    }
                                @endphp
                                <img src="{{asset($news[0]->image)}}" id="preview" class="img-fluid"
                                    width="150px">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" id="title" class="form-control form-control-sm" placeholder="Title..."
                                value="{{$news[0]->title}}">
                        </div>
                        <div class="form-group">
                            <label for="">Show Date</label>
                            <input type="date" name="show_date" id="showDate" class="form-control form-control-sm" value="{{ date('Y-m-d', strtotime($news[0]->show_date))}}">    
                        </div>
                        <div class="form-group">
                            <label for="">Show time</label>
                            <input type="time" name="show_time" id="showTime" class="form-control form-control-sm" value="{{ date('H:i', strtotime($news[0]->show_date))}}">    
                        </div>
                        @php
                            function isJsonString($value) {
                                $decoded = json_decode($value);
                                return $decoded !== null && json_last_error() === JSON_ERROR_NONE;
                            }
                        @endphp
                         @if (isJsonString($news[0]->content))
                            <div class="form-group">
                                <label for="">Content</label>
                                <div id="editorjs" style="min-height: 500px; background-color:rgba(0,0,0,.03);" data-image_upload="{{route('admin.editorjs.upload', ['_token' => csrf_token() ])}}" data-link_upload="{{route('admin.editorjs.link', ['_token' => csrf_token() ])}}"></div>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea class="ckeditor" name="content">{!! $news[0]->content !!}</textarea>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Kompartemen</label>
                            <select class="form-control large js-select2" style="width:100%" name="category_id" id="categoryId">
                                @foreach ($category as $cat)
                                <option value="{{$cat->category_id}}" {{$cat->category_id == $news[0]->category_id ? 'selected' : ''}}>
                                    {{$cat->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Tags</label>
                            <select class="form-control js-example-basic-multiple" name="tags[]" id="tags" multiple="multiple"
                                style="width
                                :100%" name="tags">
                                @foreach ($tag as $item)
                                <option value="{{$item->tag_id}}" @foreach ($tag_news as $itemTags)
                                    @if (in_array($item->tag_id, old('tags', [$itemTags->tag_id])))
                                    selected="selected"
                                    @endif
                                    @endforeach
                                    >{{$item->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea name="description" id="description" class="form-control"
                                rows="3">{!! $news[0]->description !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-4 ml-auto">
                        <div class="row">
                            @if (!isJsonString($news[0]->content))
                                @if ($news[0]->news_status_id == 1)
                                    <div class="col-lg-6 col-6">
                                        <input class="btn btn-secondary btn-block" style="font-size:14px;" name="save" id="save"
                                        type="submit" value="Draft">
                                    </div>
                                    <div class="col-lg-6 col-6">
                                        <input type="submit" name="save" id="save" value="Simpan" class="btn btn-korporat btn-block">
                                    </div>
                                @else
                                    <div class="col-lg-6 col-lg-12 col-6">
                                        <input type="submit" name="save" id="save" value="Simpan" class="btn btn-korporat btn-block">
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @if (isJsonString($news[0]->content))
            <div class="col-lg-6 col-lg-12 col-6">
                <input type="hidden" name="save" id="save" value="Simpan">
                <input type="hidden" id="caption" value="">
                <input type="hidden" id="typeOption" value="">
                <input type="hidden" id="linkVideo" value="">
                <input type="hidden" id="premiumContent" value="">
                <input type="submit" name="update" id="editData" class="btn btn-korporat btn-block">
            </div>
        @endif
    </div>
</section>
@endsection

@push('js')
<script type="module" src=" {{ asset('assets/js/editor.js') }}"></script>
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('content', {
        filebrowserUploadUrl: "{{route('profile.ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });

    var listBankImageDefault = {!! json_encode($list_bank_image) !!};
    var detailNews = {!! json_encode($news[0]) !!};
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
<script src="{{ asset('assets/js/bank-image-window.js') }}"></script>
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
