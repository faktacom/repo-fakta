@extends('layouts.front')


@section('title' , 'Reset Password')

@section('content')
<div class="container my-5" id="fkt-user-container">
    <div class="row justify-content-center" >
        <div class="col-md-5">
            <h4 class="font-weight-bold text-center">Lupa Password</h4>
            <p style="font-size: 14px" class="text-center">Silahkan masukan password baru anda</p>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus style="border-radius: 0">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                </div>

                <div class="form-group">
                    <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="new-password" placeholder="Password" style="border-radius: 0;">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                </div>

                <div class="form-group">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required autocomplete="new-password" placeholder="Konfirmasi Password" style="border-radius: 0">
                </div>

                <button class="btn btn-korporat btn-block" type="submit" style="border-radius: 0">Kirim</button>
            </form>
        </div>
    </div>
</div>
@endsection
