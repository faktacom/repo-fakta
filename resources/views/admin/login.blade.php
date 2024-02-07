<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/img/logo-fakta-title.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    {{--
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    --}}

    <link rel="stylesheet" href="{{asset('assets/icons/font-awesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">

    <title>Fakta | Admin Login</title>
</head>

<body style="background-image:url({{asset('assets/images/background/login-register.jpg')}}); background-repeat:no-repeat;background-size:100% 100vh;">
    <div class="container">
        <div class="login-wrapper">
            <div class="login-image">
                <img src="{{asset('assets/img/logo-fakta.png')}}" alt="">
            </div>
            <div class="login-form">
                <h3 class="font-weight-bold mb-2">Sign-in</h3>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="source_login" value="backend">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" autofocus style="border-radius:0">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password" style="border-radius:0">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="btn btn-korporat btn-block" style="border-radius: 0">Masuk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="container">
        <div class="row" style="background-color: antiquewhite; padding:10px;">
            <div class="col-md-4">
                <img src="" alt="">
            </div>
            <div class="col-md-8">
                <h3 class="font-weight-bold mb-2">Sign-in</h3>
                <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" autofocus style="border-radius:0">

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password" style="border-radius:0">

        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="pt-3">
        <button type="submit" class="btn btn-korporat btn-block" style="border-radius: 0">Masuk</button>
    </div>
    </form>
    </div>
    </div>
    </div> --}}
</body>

</html>