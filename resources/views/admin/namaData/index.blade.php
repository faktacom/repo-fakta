@extends('layouts.admin')

@section('title', 'Nama Data')




@push('js')
<script src="{{asset('assets/node_modules/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('assets/node_modules/tiny-editable/mindmup-editabletable.js')}}"></script>
<script src="{{asset('assets/node_modules/tiny-editable/numeric-input-example.js')}}"></script>
<script>
    $('#mainTable').editableTableWidget().numericInputExample().find('td:first').focus();
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
                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
            @if (\App\Models\Admin\ListNamaData::count() == 0)
            <button type="button" class="btn btn-info d-inline-block m-l-15" data-toggle="modal"
                data-target="#myModal"><i class="fa fa-plus-circle"></i> Create
                @yield('title')</button>
            @endif
        </div>
    </div>
    @if (\App\Models\Admin\ListNamaData::count() == 0)
    <div id="myModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Create @yield('title')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="{{route('admin.namaData.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" name="nama" class="form-control form-control-sm">
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
    @endif
</div>
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered" id="editable-datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (\App\Models\Admin\ListNamaData::count() > 0)
                    <tr id="1" class="gradeX">
                        <td>1</td>
                        <td>{{$list_nama_data->nama}}</td>
                        <td>
                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#editModal{{$list_nama_data->id}}"><i class="icon-pencil"></i></button>
                            <button class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteModal{{$list_nama_data->id}}"><i class="icon-trash"></i></button>
                        </td>
                    </tr>

                    <div id="deleteModal{{$list_nama_data->id}}" class="modal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Delete @yield('title')</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <form action="{{route('admin.namaData.destroy', $list_nama_data->id)}}" method="post">
                                    @csrf
                                    <div class="modal-body text-center">
                                        <h6>Are you sure delete?</h6>
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


                    <div id="editModal{{$list_nama_data->id}}" class="modal" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Update @yield('title')</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <form action="{{route('admin.namaData.update', $list_nama_data->id)}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="">Nama</label>
                                            <input type="text" name="nama" class="form-control form-control-sm" value="{{$list_nama_data->nama}}">
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
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
