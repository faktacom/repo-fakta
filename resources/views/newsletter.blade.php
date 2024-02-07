<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Newsletter</title>
    <style>
        .newsletter-container{
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .newsletter-wrapper{
            background-color: #f4f4f4;
            border-radius: 10px;
            display: grid;
            grid-template-rows: auto 1fr auto;
            justify-items: center;
            align-items: center;
            padding: 20px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .newsletter-header{
            width: 100px;
            height: 60px;
        }

        .newsletter-header img{
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .newsletter-footer,
        .newsletter-body{
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="newsletter-container">
        <div class="newsletter-wrapper">
            <div class="newsletter-header">
                <img src="{{ asset('assets/images/logo-fakta.png') }}" alt="">
            </div>
            <hr>
            <div class="newsletter-body">
                <h4>Halo user Fakta</h4>
                <p>email anda berhasil ditambahkan untuk menerima newsletter</p>
            </div>
            <hr>
            <div class="newsletter-footer">
                <small>{{ $website }}</small>
            </div>
        </div>
    </div>
</body>
</html>