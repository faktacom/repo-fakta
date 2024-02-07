@extends('layouts.front')

@section('title', 'Profile Settings Password')


@section('content')
<div class="row justify-content-center" id="fkt-user-container">
    <div class="col-lg-12">
        <h1 class="text-center my-4 d-lg-block d-none font-weight-bold">
            Settings Password
        </h1>

    </div>
    <div class="col-lg-8 p-1">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
        </div>
        @endif

        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
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
        

        <div class="card">
            <div class="card-body" id="fkt-password-font-size">
               <form action="{{route('profile.passwordUpdate', Auth::user()->user_id)}}" method="post">
                   @csrf
                    <div class="form-group">
                    <label for="">Current Password <small style="color: red;">*</small></label>
                    <input type="password" name="current-password" class="form-control form-control-sm">
                </div>

                <div class="form-group">
                    <label for="">New Password <small style="color: red;">*</small></label>
                    <input type="password" name="new-password" class="form-control form-control-sm">
                </div>

                <div class="form-group">
                    <label for="">New Confirm Password <small style="color: red;">*</small></label>
                    <input type="password" name="new-confirm-password" class="form-control form-control-sm">
                </div>

                <button type="submit" class="btn btn-korporat btn-block">
                    Change Password
                </button>
               </form>
            </div>
        </div>
    </div>
</div>
@endsection
