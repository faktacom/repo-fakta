@extends('layouts.admin')

@section('title', 'Tags')




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
            <button type="button" class="btn btn-info d-inline-block m-l-15" data-toggle="modal"
                data-target="#myModal"><i class="fa fa-plus-circle"></i> Create
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
                <form action="{{route('admin.tag.store')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control form-control-sm" value="{{old('title')}}">
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
                        <th>Title</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_tag as $item)
                    <tr id="1" class="gradeX">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->title}}</td>
                        <td style="text-align:center;">
                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#editModal{{$item->tag_id}}"><i class="icon-pencil"></i></button>
                            <button class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteModal{{$item->tag_id}}"><i class="icon-trash"></i></button>
                        </td>
                    </tr>

                    <div id="deleteModal{{$item->tag_id}}" class="modal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Delete @yield('title')</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <form action="{{route('admin.tag.destroy', $item->tag_id)}}" method="post">
                                    @csrf
                                    <div class="modal-body text-center">
                                        <h6>Are you sure delete {{$item->title}} tags?</h6>
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


                    <div id="editModal{{$item->tag_id}}" class="modal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Update @yield('title')</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <form action="{{route('admin.tag.update', $item->tag_id)}}" method="post">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="">Title</label>
                                            <input type="text" name="title" class="form-control form-control-sm"
                                                value="{{$item->title}}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info waves-effect"
                                            data-dismiss="modal">Close</button>
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
            {{-- <div class="row justify-content-sm-between" style="padding: 0 10px">
                <div class="form-group" >
                    @php
                        $perPage = $list_tag->perPage();
                        $currentPage = $list_tag->currentPage();
                        $total = $list_tag->total();

                        $start = ($currentPage - 1) * $perPage + 1;
                        $end = min($currentPage * $perPage, $total);

                        if ($total > 0) {
                            echo "Showing $start to $end of $total entries";
                        } else {
                            echo "No entries found";
                        }
                    @endphp
                </div>
                {{$list_tag->onEachSide(1)->links('pagination::bootstrap-4')}}
            </div> --}}
        </div>
    </div>
</div>
@endsection