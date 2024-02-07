@extends('layouts.admin')

@section('title', 'Profile')

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

@endpush

@section('content')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">Profile</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card">
            <div class="card-body">
                <center class="m-t-30">
                    @if ($detail_user->profile_picture == NULL)
                    <img src="{{asset('assets/images/profile/no-profile.jpg')}}" class="krp-detail-user-image" />
                    @else
                    <img src="{{asset('assets/images/profile/' . $detail_user->profile_picture)}}"
                        class="krp-detail-user-image" />
                    @endif
                    <h4 class="card-title m-t-10">{{$detail_user->name}}</h4>
                    <h6 class="card-subtitle">{{$detail_user->role_name}}</h6>
                </center>
            </div>
            <div>
                <hr>
            </div>
            <div class="card-body">
                <small class="text-muted">Email address </small>
                <h6>{{$detail_user->email}}</h6>

                <small class="text-muted mt-2">Tanggal Lahir </small>
                <h6>{{$detail_user->birth_date}}</h6>

                <small class="text-muted mt-2">Jenis Kelamin </small>
                <h6>{{$detail_user->gender}}</h6>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs profile-tab" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#settings"
                        role="tab">Settings</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="settings" role="tabpanel">
                    <div class="card-body">
                        <form class="form-horizontal form-material" enctype="multipart/form-data" method="POST"
                            action="{{route('admin.profileUpdate', $detail_user->user_id)}}">
                            @csrf
                            <div class="form-group">
                                <label class="col-md-12">Profile</label>
                                <div class="col-md-12">
                                    <input type="file" name="profile_picture" class="form-control form-control-line">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Name</label>
                                <div class="col-md-12">
                                    <input type="text" name="name" value="{{$detail_user->name}}"
                                        class="form-control form-control-line">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="example-email" class="col-md-12">Email</label>
                                <div class="col-md-12">
                                    <input type="email" class="form-control form-control-line" id="example-email"
                                        readonly value="{{$detail_user->email}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="example-email" class="col-md-12">Tanggal Lahir</label>
                                <div class="col-md-12">
                                    <input type="date" name="birth_date" class="form-control form-control-line"
                                        id="example-email" value="{{$detail_user->birth_date}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="example-email" class="col-md-12">Jenis Kelamin</label>
                                <div class="col-md-12 mt-2">
                                    <div class="form-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="lakiLaki"
                                                value="Laki-laki" {{$detail_user->gender == "Laki-laki" ? 'checked' :
                                            ''}}>
                                            <label class="form-check-label" for="lakiLaki">Laki-laki</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="perempuan"
                                                value="Perempuan" {{$detail_user->gender == "Perempuan" ? 'checked' :
                                            ''}}>
                                            <label class="form-check-label" for="perempuan">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Password</label>
                                <div class="col-md-12">
                                    <input type="password" name="password" class="form-control form-control-line">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Confirm Password</label>
                                <div class="col-md-12">
                                    <input type="password" name="confirm-password"
                                        class="form-control form-control-line">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Biography</label>
                                <div class="col-md-12">
                                    <textarea rows="5" name="biography"
                                        class="form-control form-control-line">{{$detail_user->biography}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="btn btn-success">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
@endsection