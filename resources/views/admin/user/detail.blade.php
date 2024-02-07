@extends('layouts.admin')

@section('title', 'Detail')

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
                <li class="breadcrumb-item"><a href="{{route('admin.user.index')}}">User</a></li>
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
            <a href="{{route('admin.user.create')}}" class="btn btn-info d-inline-block m-l-15"><i
                    class="fa fa-plus-circle"></i> Create
                @yield('title')</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <center class="m-t-30">
                    @if ($detail_user->profile_picture == NULL)
                    <img src="{{asset('assets/images/profile/no-profile.jpg')}}" class="krp-detail-user-image" alt="">
                    @else
                    <img src="{{asset('assets/images/profile/' . $detail_user->profile_picture)}}"
                        class="krp-detail-user-image" alt="">
                    @endif
                    <h4 class="card-title m-t-10">{{ $detail_user->name }}</h4>
                </center>
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <a href="{{route('admin.user.edit', $detail_user->user_id)}}" class="btn btn-success"><i
                            class="fa fa-pen"></i> Edit</a>
                </div>
                <small class="text-muted">Email address </small>
                <h6>{{$detail_user->email}}</h6>

                <small class="text-muted mt-2">Tanggal Lahir </small>
                <h6>{{$detail_user->birth_date}}</h6>

                <small class="text-muted mt-2">Jenis Kelamin </small>
                <h6>{{{$detail_user->gender}}}</h6>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="editable-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Action</th>
                                <th>URL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_user_log as $item)
                            <tr id="1" class="gradeX">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->created_date}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->action_code}}</td>
                                <td>{{$item->action_name}}</td>
                                <td>{{$item->url}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection