@extends('layouts.front')

@section('title', 'Profile '. $user->name)

@push('css')
<style>
    .card .cover-photo {
        position: relative;
        /* height: 350px; */
        height: 283px;
        background-size: 100% 100%;
        background-repeat: no-repeat no-repeat;
    }

    .photo {
        display: block;
        border-radius: 50%;
        margin: 0px auto 10px;
        border: 5px solid white;
        width: 120px;
        height: 120px;
        max-width: 120px;
        max-height: 120px;
    }

    .attribution a {
        color: hsl(228, 45%, 44%);
    }

    .btn-auth {
        position: relative;
        overflow: hidden;
    }

    .btn-auth input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        cursor: inherit;
        display: block;
    }

    .btn-profile {
        color: white;
        background-color: #b81f24;
        padding: 5px 20px;
        font-size: 13px;
    }

    .btn-profile:hover {
        color: white;
    }

    .file-cover {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center" id="fkt-user-container">
    <div class="col-lg-12">
        <h1 class="text-center my-4 d-lg-block d-none font-weight-bold">
            Profile Setting
        </h1>

    </div>
    <div class="col-lg-8 p-1">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{route('profile.user.update', $user->user_id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card w-100">
                @if ($user->cover_picture == NULL)
                <div class="cover-photo" style="background-color: gray">
                    @else
                    <div class="cover-photo" style="background-image: url({{asset('assets/images/profile/' . $user->cover_picture)}})">
                        @endif
                        <span class="btn btn-profile btn-auth file-cover">
                            Unggah Foto Sampul <input type="file" name="cover" id="">
                        </span>
                        <div style="position: relative; top:210px;">
                            @if ($user->profile_picture == NULL)
                            <img class="photo" src="{{asset('assets/images/profile/no-profile.jpg')}}" alt="Profile {{Auth::user()->name}}">
                            @else
                            <img class="photo" src="{{asset('assets/images/profile/' . $user->profile_picture)}}" alt="Profile {{Auth::user()->name}}">
                            @endif
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-4 col-7 mx-auto">
                                        <span class="btn btn-profile btn-auth text-center d-block">
                                            Upload Profile Photo <input type="file" class="d-block" name="profile" id="">
                                        </span>
                                    </div>
                                </div>
                                <span class="d-block text-center">Image upload max 2MB</span>
                            </div>
                        </div>
                        
                    </div>

                    <div class="card-body">
                        <div class="container" style="margin-top: 120px">
                            <div class="form-group">
                                <label>Name <small style="color: red">*</small></label>
                                <input type="text" name="name" class="form-control form-control-sm" value="{{$user->name}}">
                            </div>

                            <div class="form-group">
                                <label>Biography <small style="color: red">*</small></label>
                                <textarea name="biography" class="form-control" rows="3" placeholder="Silahkan isi sesuai dengan kegiatan serta profesi anda saat ini..">{{$user->biography}}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Birth Date</label>
                                <input type="date" name="birth_date" class="form-control form-control-sm" value="{{$user->birth_date}}">
                            </div>

                            <div class="form-group">
                                <label for="" class="d-block">
                                    Gender
                                </label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="lakiLaki" value="Laki-Laki" {{$user->gender == "Laki-Laki" ? 'checked' : ''}}>
                                    <label class="form-check-label" for="lakiLaki">Men</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="perempuan" value="Perempuan" {{$user->gender == "Perempuan" ? 'checked' : ''}}>
                                    <label class="form-check-label" for="perempuan">Woman</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-profile btn-block">Save Changes</button>
                        </div>
                    </div>

                </div>

        </form>
    </div>
</div>
@endsection