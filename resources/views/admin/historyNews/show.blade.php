@extends('layouts.admin')

@section('title', $detail_history[0]->title)


@push('css')
{{--
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'> --}}
@endpush


@push('js')
<script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
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
    <div class="col-md-12 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.news.index')}}">News</a></li>
            <li class="breadcrumb-item"><a
                    href="{{route('admin.historyNews.index', $detail_history[0]->news_id)}}">History Update</a></li>
            <li class="breadcrumb-item active">{{$detail_history[0]->title}}</li>
        </ol>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@yield('title')</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Image</th>
                    <td><img src="{{asset('assets/news/images/' . $detail_history[0]->image)}}" class="img-fluid"
                            width="120" onerror="replaceMissingImage(this,'{{$detail_history[0]->image}}')"></td>       
                </tr>
                <tr>
                    <th>Title</th>
                    <td>{{$detail_history[0]->title}}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{!! $detail_history[0]->description !!}</td>
                </tr>
                @php
                    function isJsonString($value) {
                        $decoded = json_decode($value);
                        return $decoded !== null && json_last_error() === JSON_ERROR_NONE;
                    }
                @endphp
                <tr>
                    <th>Content</th>
                    @if (isJsonString($detail_history[0]->content))
                        @php
                        $content = json_decode($detail_history[0]->content);
                        @endphp
                        <td>
                            @foreach ($content->blocks as $block)
                                @if ($block->type === 'header')
                                    <h{{ $block->data->level }}>{!! $block->data->text !!}</h{{ $block->data->level }}>
                                @elseif ($block->type === 'paragraph')
                                    <p>{!! $block->data->text !!}</p>
                                @elseif ($block->type === 'list')
                                    @if ($block->data->style === 'ordered')
                                        <ol>
                                    @else
                                        <ul>
                                    @endif
                                    @foreach ($block->data->items as $item)
                                        <li>{!! $item !!}</li>
                                    @endforeach
                                    @if ($block->data->style === 'ordered')
                                        </ol>
                                    @else
                                        </ul>
                                    @endif
                                @elseif ($block->type === 'linktool')
                                    <a href="{{ $block->data->link }}" target="_blank">{{ $block->data->link }}</a>
                                    @elseif ($block->type === 'table')
                                    <table>
                                        <tbody>
                                            @foreach ($block->data->content as $row)
                                                <tr>
                                                    @foreach ($row as $cell)
                                                        <td>{!! $cell !!}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @elseif ($block->type === 'quote')
                                    <blockquote>{!! $block->data->text !!}</blockquote>
                                @elseif ($block->type === 'image')
                                    <img src="{{ $block->data->file->url }}" alt="{{ $block->data->caption }}" style="width: 100%">
                                    <small>{{ $block->data->caption }}</small>
                                @endif
                            @endforeach
                        </td>
                    @else
                        <td>{!! $detail_history[0]->content !!}</td>
                    @endif
                </tr>
                <tr>
                    <th>Kompartement</th>
                    <td><span class="badge badge-info">{{$detail_history[0]->category_name}}</span></td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td><span class="badge badge-info">{{$detail_history[0]->creator_name}}</span></td>
                </tr>
                <tr>
                    <th>Edited By</th>
                    <td><span class="badge badge-info">
                        @if (isset($detail_history[0]->editor_name))
                            {{$detail_history[0]->editor_name}}
                        @else
                            -
                        @endif
                    </span></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection