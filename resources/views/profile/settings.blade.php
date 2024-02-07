@extends('layouts.front')

@section('title', 'Profile Settings')


@push('css')
<style>
    .card a {
        text-decoration: none;
        color: black;
    }

    .card i {
        font-size: 30px;
    }

    .card span {
        font-size: 15px;
        display: block;
        -webkit-font-smoothing: antialiased;
    }

    .card span:last-child {
        font-size: 13px;
        color: rgb(118, 118, 118);
    }

    .card a::after {
        content: '';
        height: 1px;
        width: 100%;
        background-color: #e2e3e5;
        display: block;
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
        <div class="card">
            <a href="{{route('profile.user')}}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-1 col-2 my-auto">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="col-lg-11 col-9">
                            <span>Profile</span>
                            <span>Complete your profile</span>
                        </div>
                    </div>
                </div>
            </a>
            <a href="#">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-1 col-2 my-auto">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="col-lg-11 col-9">
                            <span>Email</span>
                            <span>{{Auth::user()->email}}</span>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{route('profile.password')}}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-1 col-2 my-auto">
                            <i class="fa fa-lock"></i>
                        </div>
                        <div class="col-lg-11 col-9">
                            <span>Password</span>
                            <span>******</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
</div>
@endsection
