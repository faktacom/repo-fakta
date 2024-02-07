@extends('layouts.admin')

@section('title', 'Webinar')

@push('js')
<script src="{{asset('assets/node_modules/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script>
    $(document).ready(function () {
        $('#editable-datatable').DataTable();
    });

</script>

@if (Session::has('success'))
<script>
    $(function () {
        $.toast({
            heading: '{{session('success')}}',
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
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
            <a href="{{route('admin.webinar.create')}}" class="btn btn-info d-inline-block m-l-15"><i
                    class="fa fa-plus-circle"></i> Create
                @yield('title')</a>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="editable-datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Schedule</th>
                            <th>Kompartemen</th>
                            <th style="width:180px; text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listWebinar as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$loop->iteration}}</td>
                            <td><img src="{{asset('assets/images/webinar/'. $item->featured_image)}}" class="img-fluid" width="80">
                            <td>{{$item->title}}</td>
                            <td>{{$item->priority}}</td>
                            <td>{{date("d-m-Y",strtotime($item->schedule))}}</td>
                            <td>{{$item->category_name}}</td>
                            <td style="text-align:center;">
                                <a href="{{ route('admin.webinar.participant', $item->webinar_id) }}" class="btn btn-info btn-sm">
                                    <i class="icon-eye"></i>
                                </a>
                                <a href="{{ route('admin.webinar.edit', $item->webinar_id) }}" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{$item->webinar_id}}">
                                    <i class="icon-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <div id="deleteModal{{$item->webinar_id}}" class="modal" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Delete @yield('title')</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">×</button>
                                    </div>
                                    <form action="{{route('admin.webinar.destroy', $item->webinar_id)}}" method="post">
                                        @csrf
                                        <div class="modal-body text-center">
                                            <h6>Are you sure delete webinar "{{$item->title}}"?</h6>
                                            <button type="button" class="btn btn-success waves-effect"
                                                data-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-danger waves-effect">Yes</button>
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
</div>
@endsection