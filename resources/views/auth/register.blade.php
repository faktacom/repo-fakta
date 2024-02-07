@extends('layouts.front')

@section('title', 'Daftar')

@section('content')
<div class="container my-5" style="grid-column-start: span 12">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="text-center font-weight-bold mb-2">Register</h3>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name') }}" placeholder="Name" autocomplete="name" autofocus
                        style="border-radius:0;">

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                        placeholder="Email" value="{{ old('email') }}" autocomplete="email" style="border-radius:0;">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" autocomplete="new-password" placeholder="Password" style="border-radius:0;">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                        autocomplete="new-password" placeholder="Password Confirm" style="border-radius:0;">
                </div>
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="6LcWHJglAAAAABCfe_s-odcOoEcoAyqH2xaqwcb1"></div>
                    @error('g-recaptcha-response')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-korporat btn-block" style="border-radius:0;">Register</button>
                <small class="d-block text-center mt-2 font-size-login">Already have account ? <a href="{{route('login')}}">Login
                        Now</a></small>
            </form>
        </div>
    </div>
</div>
@endsection