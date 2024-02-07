@extends('layouts.admin')

@section('title', 'Edit User')


@push('js')

@if (Session::has('error'))
<script>
    $(function () {
        $.toast({
            heading: '{{session('error')}}',
            position: 'top-right',
            loaderBg: '#ff6849',
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
                <li class="breadcrumb-item"><a href="{{route('admin.user.detail', $detail_user->user_id)}}">Detail</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</div>
<div class="card-group">
    <div class="card">
        <form action="{{route('admin.user.update', $detail_user->user_id)}}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" value="{{$detail_user->name}}" class="form-control form-control-sm">
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control form-control-sm"
                        value="{{$detail_user->email}}">
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" name="password" class="form-control form-control-sm">
                </div>
                <div class="form-group">
                    <label for="">Confirm Password</label>
                    <input type="password" name="confirm-password" class="form-control form-control-sm">
                </div>

                <div class="form-group">
                    <label for="">Role</label>
                    <select class="form-control js-select2" name="role">
                        @foreach ($list_role as $item)
                        <option value="{{$item->role_id}}" {{$item->role_id == $detail_user->role_id ? 'selected' : ''}}
                            >{{$item->role_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm d-block ml-auto ">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection