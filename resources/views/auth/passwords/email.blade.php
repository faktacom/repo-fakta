@extends('layouts.front')

@section('title' ,'Reset Password')

@section('content')
<div class="container my-5" style="grid-column-start: span 12">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="text-center font-weight-bold mb-2">Lupa Password</h3>
            <p class="text-center" style="font-size: 14px">Masukan Email untuk mengganti password</p>

            @if (session('status'))
            <div class="alert alert-success my-2" role="alert" style="border-radius: 0">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email"
                        autofocus style="border-radius: 0">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button class="btn btn-korporat btn-block" style="border-radius: 0" type="submit">Kirim</button>

                
            </form>
        </div>
    </div>
</div>
@endsection
