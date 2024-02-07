@extends('layouts.admin')

@section('title', 'Footer')




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
            <a href="{{route('admin.footer.create')}}" class="btn btn-info d-inline-block m-l-15"><i
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
                            <th>Title</th>
                            <th>Content</th>
                            <th>Link</th>
                            <th>Link Type</th>
                            <th style="min-width:100px; text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_footer_link as $item)
                        <tr id="1" class="gradeX">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->title}}</td>
                            <td>{!! \Str::limit($item->content, 150) !!}</td>
                            <td>{{$item->link}}</td>
                            <td>{{$item->link_type}}</td>
                            <td style="text-align:center;">
                                @if($item->slug == "terms-of-service")
                                <a href="{{route('admin.termsOfService.index')}}"
                                    class="btn btn-info btn-sm"><i class="icon-clock"></i></a>
                                @endif
                                @if($item->slug == "privacy-policy")
                                <a href="{{route('admin.privacyPolicy.index')}}"
                                    class="btn btn-info btn-sm"><i class="icon-clock"></i></a>
                                @endif
                                <a href="{{route('admin.footer.edit', $item->link_id)}}" class="btn btn-info btn-sm"><i
                                        class="icon-pencil"></i></a>

                                <button class="btn btn-danger btn-sm" data-toggle="modal"
                                    data-target="#deleteModal{{$item->link_id}}"><i class="icon-trash"></i></button>
                            </td>
                        </tr>

                        <div id="deleteModal{{$item->link_id}}" class="modal" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Delete @yield('title')</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">×</button>
                                    </div>
                                    <form action="{{route('admin.footer.destroy', $item->link_id)}}" method="post">
                                        @csrf
                                        <div class="modal-body text-center">
                                            <h6>Are you sure delete this?</h6>
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