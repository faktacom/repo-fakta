@extends('layouts.admin')

@section('title', 'Video')




@push('js')
<script src="{{asset('assets/node_modules/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#editable-datatable').DataTable();
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@if (Session::has('success'))
<script>
    $(function() {
        $.toast({
            heading: '{{session('
            success ')}}',
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'success',
            hideAfter: 3500,
            stack: 6
        });
    });
</script>
@endif

@if (Session::has('invalid'))
<script>
    $(function() {
        $.toast({
            heading: '{{session('
            invalid ')}}',
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
    $(function() {
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
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
            <button type="button" class="btn btn-info d-inline-block m-l-15" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-circle"></i> Create
                @yield('title')</button>
        </div>
    </div>
    <div id="myModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Create @yield('title')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="{{route('admin.video.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control form-control-sm" value="{{old('title')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea name="description"rows="4" class="form-control">{{old('description')}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Category</label>
                            <select class="form-control js-select2" name="category_id">
                                @foreach ($list_category as $category)
                                @if (old('category_id') == $category->category_id)
                                <option value="{{$category->category_id}}" selected>{{$category->title}}</option>
                                @else
                                <option value="{{$category->category_id}}">{{$category->title}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Thumbnail</label>
                            <input type="file" name="image" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="">Link</label>
                            <input type="text" name="link" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="">Priority</label>
                            <input type="number" name="priority" class="form-control form-control-sm" value="{{old('priority')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered" id="editable-datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Category</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Image</th>
                        <th>Link</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_video as $video)
                    <tr id="1" class="gradeX">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$video->category_title}}</td>
                        <td>{{$video->video_title}}</td>
                        <td>{{$video->priority}}</td>
                        <td><img src="{{asset('assets/images/video/' . $video->image)}}" width="80"></td>
                        <td>{{$video->link}}</td>
                        <td style="text-align:center; width:91.0312px;">
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editModal{{$video->video_id}}"><i class="icon-pencil"></i></button>
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{$video->video_id}}"><i class="icon-trash"></i></button>
                        </td>
                    </tr>
                    <div id="deleteModal{{$video->video_id}}" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Delete @yield('title')</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <form action="{{route('admin.video.destroy', $video->video_id)}}" method="post">
                                    @csrf
                                    <div class="modal-body text-center">
                                        <h6>Are you sure delete?</h6>
                                        <button type="button" class="btn btn-success waves-effect" data-dismiss="modal">No</button>
                                        <button type="submit" class="btn btn-danger waves-effect">Yes</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>


                    <div id="editModal{{$video->video_id}}" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Update @yield('title')</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <form action="{{route('admin.video.update', $video->video_id)}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="">Title</label>
                                            <input type="text" name="title" class="form-control form-control-sm" value="{{$video->video_title}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <textarea name="description"rows="4" class="form-control">{{$video->video_description}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Category</label>
                                            <select class="form-control js-select2" style="width:100%" name="category_id">
                                                @foreach ($list_category as $item)
                                                <option value="{{$item->category_id}}" {{$item->category_id ==
                                                    $video->category_id ? 'selected' : ''}}>{{$item->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Thumbnail</label>
                                            <input type="file" name="image" class="form-control form-control-sm" onchange="readURL(this);">

                                            <div class="mt-3">
                                                <img src="{{asset('assets/images/video/' . $video->image)}}" id="preview" width="100" class="img-fluid">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Link</label>
                                            <input type="text" name="link" class="form-control form-control-sm" value="{{$video->link}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Priority</label>
                                            <input type="number" name="priority" class="form-control form-control-sm" value="{{$video->priority}}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success waves-effect">Save</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection