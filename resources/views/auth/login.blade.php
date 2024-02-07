
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:type" content="Website">
    <meta name="description" content="Website Fakta">
    <meta name="keywords" content="Fakta">
    <meta property="og:title" content="Fakta">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('assets/img/logo-fakta-title.png') }}">
    <meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:image:alt" content="Login">
    <meta property="og:description" content="Website Fakta">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/img/logo-fakta-title.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css?v=1.3')}}">
    <link rel="stylesheet" href="{{asset('assets/icons/font-awesome/css/all.min.css?v=1.3')}}">

    @stack('css')
    <link rel="stylesheet" href="{{asset('assets/css/style.css?v=1.3')}}">
    <title>Fakta | Login</title>
</head>
<body>
    <div class="fkt-login-wrapper">
        <div class="fkt-login-container">
            <div class="fkt-login-img" style="background-image: url({{asset('assets/images/login.jpg')}})">
            </div>
            
            <div class="fkt-login-form-container">
                <div class="fkt-login-logo">
                    <a href="{{ route('welcome') }}">
                        <img src="{{asset('assets/img/logo-fakta.png')}}" alt="">
                    </a>
                </div>
                <div>
                    <div class="fkt-login-form">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <span class="fkt-login-form-header"><b>Selamat Datang</b></span>
                        </br>
                            <span>Mohon memasukan detail login di bawah</span>
                            <input type="hidden" name="source_login" value="frontend">
                            <div class="fkt-login-form-input">
                                <span><b>Alamat Email</b></span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter Your Email" autofocus style="border-radius:0">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                </span>
                                 @enderror
                            </div>
                            <div class="fkt-login-form-input">
                                <span><b>Kata Sandi</b></span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="*********" style="border-radius:0">
    
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <a href="{{ route('password.request') }}" class="fkt-login-form-forgot">Lupa Kata Sandi</a>
                            <button type="submit" class="btn btn-korporat btn-block" style="border-radius: 0; margin: 10px 0;">Masuk</button>
                            <span class="fkt-login-form-register">Belum Punya Akun ? <a href="{{route('register')}}"><b>Daftar Sekarang!</b></a></span>
                        </form>
                    </div>   
                </div>           
            </div>
        </div>
    </div>
    
</body>
</html>
<script src="{{asset('assets/js/jquery-3.6.0.js?v=1.7')}}"></script>
<script src="{{asset('assets/js/jquery.min.js?v=1.7')}}"></script>
<script>
    if(localStorage.getItem('mode') === 'dark'){   
        console.log("test123");
        $('body').addClass('nightview');
    }else{
        console.log("test456");
        $('body').removeClass('nightview');
    }
</script>