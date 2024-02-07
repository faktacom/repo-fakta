<!doctype html>
<html lang="en">

<head>
    @php
        $title = "Fakta";
        $image_path = asset('assets/img/open-graph-fakta.jpg');
        $url = "https://fakta.com";
        if(isset($list_news)){
            $title =  $list_news->title;
            if(strpos($list_news->image, "assets/images/bank_image/") === false){
                $image_path = "assets/news/images/".$list_news->image."?".Str::random(20);
            }else{
                $image_path = $list_news->image."?".Str::random(20);
            }
            $url = route('news.detail', ['category_slug' => $list_news->category->slug, 'slug' => $list_news->slug]);
        }
    @endphp
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta property="og:type" content="Website">
    <meta property="og:title" content="{{$title}}">
    <meta property="og:url" content="{{$url}}">
    <meta property="og:image" content="{{asset($image_path)}}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Website Fakta">
    <meta property="og:description" content="Mewarnai Indonesia">
    <meta name="description" content="Mewarnai Indonesia">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/img/logo-fakta-title.png')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css?v=2.1')}}">
    <link rel="stylesheet" href="{{asset('assets/icons/font-awesome/css/all.min.css?v=2.1')}}">
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css?v=2.1')}}">
    <link rel="stylesheet" href="{{asset('assets/css/owl.theme.default.min.css?v=2.1')}}">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-N6KYWH0S94"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-N6KYWH0S94');
    </script>
    
     <!-- Google Tag Manager -->
     <script>
     (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
     new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
     j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
     'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
     })(window,document,'script','dataLayer','GTM-PWNCPJKL');
     </script>
     <!-- End Google Tag Manager -->

    @stack('css')
    <link rel="stylesheet" href="{{asset('assets/css/style.css?v=2.1')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>Fakta | @yield('title')</title>
    <meta name="google-site-verification" content="9E-AxfiLgzq-QEV2XjfkeSx312az30PW2uAJtHO822Q" />
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PWNCPJKL"  height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    {{-- Sidebar mobile --}}
    <div class="krp-sidebar" id="mobileSidebar">
        <ul>
            <li class="krp-sidebar-menu" style="background-color: var(--color-black);">
                <button class="krp-sidebar-close dark-mod">
                    <img src="{{ asset('assets/images/icon/darkmod.png') }}" class="img-fluid" width="24">
                </button>
                <button class="krp-sidebar-close float-right" onclick="toggleSidebar()"><img src="{{ asset('assets/images/icon/close.png') }}" class="img-fluid" width="24"></button>
            </li>

            @guest
            <li class="krp-sidebar-menu">
                <a href="{{route('login')}}">
                    <img class="navbar-avatar" src="{{ asset('assets/images/profile/profile.png') }}" alt="">
                    <span style="padding-left: 5px;">Login</span>
                </a>
            </li>
            <li class="krp-sidebar-menu">
                <a href="{{route('welcome')}}" {{request()->is('/') ? 'active' : ''}}>
                    <img src="{{ asset('assets/images/icon/home.png') }}" alt="" width="24">
                    <span style="padding-left: 5px;">Home</span>
                </a>
            </li>
            @if (isset($categoryData['listCategory']))
                @foreach ($categoryData['listCategory'] as $cat)
                    @if ($cat->count_child > 0)
                        <li class="krp-sidebar-menu fkt-sidebar-parent-menu" id="fktParentMenu">
                            @if ($cat->slug == "whistler")
                                <a href="#" {{request()->is('category/' . $cat->slug)
                                    ? 'active' : ''}}>
                            @else     
                                <a href="{{route('category.detail', $cat->slug)}}" {{request()->is('category/' . $cat->slug)
                                    ? 'active' : ''}}>
                            @endif
                                <img src="{{ asset('assets/images/category-icon/'.$cat->icon_path) }}" alt="" width="24">
                                <span style="padding-left: 5px;">{{$cat->title}}</span>
                            </a>
                            <i class="fa fa-caret-down" style="color: white"></i>
                        </li>
                        <ul id="fkt-sidebar-child-menu-container" class="fkt-sidebar-child-menu-container">
                            @foreach ($categoryData['listSubCategory'] as $subcat)
                                @if ($cat->category_id == $subcat->parent_id)
                                    <li class="fkt-sidebar-child-menu-item">
                                        @if ($subcat->slug == "data" || $subcat->slug == "whistler")
                                            <a href="#" class="fkt-white-links">
                                        @else
                                            <a href="{{route('category.detail', $subcat->slug)}}" class="fkt-white-links">  
                                        @endif
                                            <img src="{{ asset('assets/images/category-icon/'.$subcat->icon_path) }}" alt="" width="24">
                                            <span style="padding-left: 5px;">{{$subcat->title}}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <li class="krp-sidebar-menu">
                            @if ($cat->slug == "whistler")
                                <a href="#" {{request()->is('category/' . $cat->slug)
                                    ? 'active' : ''}}>
                            @else
                                <a href="{{route('category.detail', $cat->slug)}}" {{request()->is('category/' . $cat->slug)
                                    ? 'active' : ''}}>
                            @endif
                                <img src="{{ asset('assets/images/category-icon/'.$cat->icon_path) }}" alt="" width="24">
                                <span style="padding-left: 5px;">{{$cat->title}}</span>
                            </a>
                        </li> 
                    @endif
                @endforeach
            @else
                <li class="krp-sidebar-menu">
                    <a href="#">
                        <span>Category not found</span>
                    </a>
                </li>
            @endif
            <li class="krp-sidebar-menu">
                <a href="{{route('footer.detail', 'about-us')}}">
                    <span>Tentang Kami</span>
                </a>
            </li>
            <li class="krp-sidebar-menu">
                <a href="{{route('footer.detail', 'pedoman-media-siber')}}">
                    <span>Pedoman Media Siber</span>
                </a>
            </li>
            <li class="krp-sidebar-menu">
                <a href="{{route('footer.detail', 'kode-etik-jurnalistik')}}">
                    <span>Kode Etik Jurnalistik</span>
                </a>
            </li>
            <li class="krp-sidebar-menu">
                <a href="{{route('footer.detail', 'privacy-policy')}}">
                    <span>Kebijakan Privasi</span>
                </a>
            </li>
            @else
            <li class="krp-sidebar-menu">
                <a {{request()->is(Auth::user()->username) ? 'active' : ''}} href="{{route('profile.detail',
                    Auth::user()->username)}}">
                    <div class="krp-sidebar-profile">
                        @if (Auth::user()->profile_picture == NULL)
                        <img class="img-fluid" src="{{ asset('assets/images/profile/profile-sidebar.png') }}" alt="Profile {{Auth::user()->name}}">
                        @else
                        <img class="img-fluid" src="{{asset('assets/images/profile/' . Auth::user()->profile_picture)}}" alt="Profile {{Auth::user()->name}}">
                        @endif
                        <div class="krp-sidebar-text">
                            <h5><strong>{{Auth::user()->name}}</strong></h5>
                            <h6>{{Auth::user()->email}}</h6>
                        </div>
                    </div>
                </a>
            </li>
            <li class="krp-sidebar-menu">
                <a href="{{route('welcome')}}" {{request()->is('/') ? 'active' : ''}}>
                    <img src="{{ asset('assets/images/icon/home.png') }}" alt="" width="24">
                    <span style="padding-left: 5px;">Home</span>
                </a>
            </li>
            @if (isset($categoryData['listCategory']))
                @foreach ($categoryData['listCategory'] as $cat)
                    @if ($cat->count_child > 0)
                        <li class="krp-sidebar-menu fkt-sidebar-parent-menu" id="fktParentMenu">
                            @if ($cat->slug == "whistler")
                                <a href="#" {{request()->is('category/' . $cat->slug)
                                    ? 'active' : ''}}>
                            @else     
                                <a href="{{route('category.detail', $cat->slug)}}" {{request()->is('category/' . $cat->slug)
                                    ? 'active' : ''}}>
                            @endif
                                <img src="{{ asset('assets/images/category-icon/'.$cat->icon_path) }}" alt="" width="24">
                                <span style="padding-left: 5px;">{{$cat->title}}</span>
                            </a>
                            <i class="fa fa-caret-down" style="color: white"></i>
                        </li>
                        <ul id="fkt-sidebar-child-menu-container" class="fkt-sidebar-child-menu-container">
                            @foreach ($categoryData['listSubCategory'] as $subcat)
                                @if ($cat->category_id == $subcat->parent_id)
                                    <li>
                                        @if ($subcat->slug == "data" || $subcat->slug == "whistler")
                                            <a href="#" class="fkt-white-links">
                                        @else
                                            <a href="{{route('category.detail', $subcat->slug)}}" class="fkt-white-links">  
                                        @endif
                                            <img src="{{ asset('assets/images/category-icon/'.$subcat->icon_path) }}" alt="" width="24">
                                            <span style="padding-left: 5px;">{{$subcat->title}}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <li class="krp-sidebar-menu">
                            @if ($cat->slug == "whistler")
                                <a href="#" {{request()->is('category/' . $cat->slug)
                                    ? 'active' : ''}}>
                            @else
                                <a href="{{route('category.detail', $cat->slug)}}" {{request()->is('category/' . $cat->slug)
                                    ? 'active' : ''}}>
                            @endif
                                <img src="{{ asset('assets/images/category-icon/'.$cat->icon_path) }}" alt="" width="24">
                                <span style="padding-left: 5px;">{{$cat->title}}</span>
                            </a>
                        </li> 
                    @endif
                @endforeach
            @else
                <li class="krp-sidebar-menu">
                    <a href="#">
                        <span>Category not found</span>
                    </a>
                </li>
            @endif
            @if (session()->get('role_id') != 4)
                <li class="krp-sidebar-menu">
                    <a href="{{route('admin.home')}}" target="_blank">
                        <span>Dashboard</span>
                    </a>
                </li>
            @endif
            @if (session()->get('role_id') != 4 )
                <li class="krp-sidebar-menu">
                    <a href="{{route('profile.myContent')}}" target="_blank">
                        <span>My Content</span>
                    </a>
                </li>
            @endif
            <li class="krp-sidebar-menu">
                <a href="{{route('footer.detail', 'about-us')}}">
                    <span>Tentang Kami</span>
                </a>
            </li>
            <li class="krp-sidebar-menu">
                <a href="{{route('footer.detail', 'pedoman-media-siber')}}">
                    <span>Pedoman Media Siber</span>
                </a>
            </li>
            <li class="krp-sidebar-menu">
                <a href="{{route('footer.detail', 'kode-etik-jurnalistik')}}">
                    <span>Kode Etik Jurnalistik</span>
                </a>
            </li>
            <li class="krp-sidebar-menu">
                <a href="{{route('footer.detail', 'privacy-policy')}}">
                    <span>Kebijakan Privasi</span>
                </a>
            </li>
            <li class="krp-sidebar-menu">
                <a style="color: var(--color-white);" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><strong>Logout</strong></a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
            @endguest
        </ul>
    </div>

    <main id="scrollDiv">
        <div class="krp-home-wrapper">
            <div class="shadow-sm bg-white nav-sticky fkt-navbar-container">
                <nav class="navbar navbar-expand-lg navbar-light bg-white fkt-navbar-header" id="fkt-navbar-header">
                    <div class="container">
                        {{-- Icon menu mobile --}}
                        <button class="krp-sidebar-open" onclick="toggleSidebar()">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        {{-- Logo mobile & desktop --}}
                        <a class="krp-navbar-brand" href="{{route('welcome')}}">
                            <img src="{{asset('assets/img/logo-fakta.png')}}" class="img-fluid d-block">
                        </a>

                        {{-- Searchbar desktop --}}
                        <ul class="navbar-nav ml-auto d-lg-flex d-none">
                            <li class="nav-item" style="width: 300px;">
                                <div class="nav-link d-lg-block d-none">
                                    <form action="{{route('front.search')}}" method="get">
                                        <div class="has-search">
                                            <span class="fa fa-search form-control-feedback"></span>
                                            <input type="text" class="form-control" placeholder="Search" name="q" value="{{old('q', \Request::get('q'))}}">
                                        </div>
                                    </form>
                                </div>
                            </li>
                        </ul>

                        <ul class="navbar-nav navbar-nav-border-left align-items-center">
                            {{-- Icon search mobile use js --}}
                            <li class="nav-item d-lg-none d-block">
                                <a class="nav-link fkt-navbar-header-search" type="button" id="searchButton">
                                    <i class="fa fa-search openclosesearch"></i>
                                    <i class="fa fa-times openclosesearch" style="display: none"></i>
                                </a>
                            </li>
                            @guest
                            {{-- Login and profile button desktop --}}
                            <li class="nav-item d-lg-block d-none">
                                <img class="navbar-avatar dark-mod" src="{{ asset('assets/images/icon/darkmod.png') }}" alt="">
                            </li>
                            <li class="nav-item d-lg-block d-none">
                                <a href="{{route('login')}}" class="nav-link btn-login"><img class="navbar-avatar" src="{{ asset('assets/images/profile/profile.png') }}" alt=""><span style="padding-left: 5px;">Login</span></a>
                            </li>
                            @else
                            <li class="nav-item d-lg-block d-none">
                                {{-- <div class="fkt-button-nightview-desktop dark-mod" style="background-image: url({{ asset('assets/images/icon/darkmod.png') }})"></div> --}}
                                <img class="navbar-avatar dark-mod" src="{{ asset('assets/images/icon/darkmod.png') }}" alt="">
                            </li>
                            <li class="nav-item d-lg-block d-none dropdown">
                                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if (Auth::user()->profile_picture == NULL)
                                    <img class="navbar-avatar" src="{{asset('assets/images/profile/profile.png')}}" alt="">
                                    @else
                                    <img class="navbar-avatar" src="{{asset('assets/images/profile/' . Auth::user()->profile_picture)}}" alt="">
                                    <span class="fkt-username"><b>{{Auth::user()->username}}</b></span>
                                    @endif
                                </a>
                                <div class="dropdown-menu menu-desktop" aria-labelledby="navbarDropdown" style="right: 0;border-radius:0;left: unset;">
                                    <a class="dropdown-item" href="{{route('profile.detail', Auth::user()->username)}}">
                                        <div class="dropdown-item">
                                            <div class="krp-dropdown-profile">
                                                @if (Auth::user()->profile_picture == NULL)
                                                <img class="navbar-avatar" src="{{asset('assets/images/profile/profile.png')}}" alt="">
                                                @else
                                                <img class="navbar-avatar" src="{{asset('assets/images/profile/' . Auth::user()->profile_picture)}}" alt="">
                                                @endif
                                                <span><strong>{{ Auth::user()->username }}</strong><br>{{
                                                    Auth::user()->email }}</span>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    @if (session()->get('role_id') != 4)
                                        <a class="dropdown-item" href="{{route('admin.home')}}" target="_blank">Dashboard</a>
                                    @endif
                                    <a class="dropdown-item" href="{{route('profile.settings')}}">Account Settings</a>
                                    @if (session()->get('role_id') != 4)
                                        <a class="dropdown-item" href="{{route('profile.myContent')}}">My Content</a>
                                    @endif
                                    <a class="dropdown-item" href="{{route('logout')}}" style="color: var(--color-red)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </ul>

                        {{-- Searchbar mobile --}}
                        <div class="container searchbardiv" id="formsearch">
                            <form role="search" action="{{route('front.search')}}" method="get" id="searchform">
                                <div class="input-group">
                                    <input type="text" id="searchbox" class="form-control" name="q" id="s" style="border-radius: 0" value="{{old('q', \Request::get('q'))}}">
                                    <div class="input-group-btn">
                                        <button class="btn btn-search" id="searchsubmit" type="submit">
                                            <strong><i class="fa fa-search"></i></strong>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </nav>

                {{-- Menu navbar category --}}
              
                <div class="fkt-navbar-menu-wrapper">
                    <div class="container">
                        <ul class="navbar-nav">
                            <li class="owl-carousel owl-theme nav-scroll " id="nav-scroll">
                                <!--<a href="{{route('welcome')}}" -->
                                <!--    class="item {{request()->is('tag/welcome') ? 'link-active' : ''}}" >-->
                                <!--    News-->
                                <!--<a href="/maintenance"-->
                                <!--    class="item" style="color:var(--color-red);">-->
                                <!--    Jobs-->
                                <!--</a>-->
                                <a href="{{route('tag.detail', ['slug' => 'pemilu-2024'])}}" 
                                    class="item {{request()->is('tag/pemilu-2024') ? 'link-active' : ''}}">
                                    Pemilu 2024
                                </a>
                                <a href="{{route('tag.detail', ['slug' => 'cekpejabat'])}}" 
                                    class="item {{request()->is('tag/cekpejabat') ? 'link-active' : ''}}" style="color:var(--color-red);">
                                    CekPejabat
                                </a>
                                <a href="{{route('category.detail', 'data')}}"
                                    class="item {{request()->is('category/data') ? 'link-active' : ''}}">
                                    Data
                                </a>
                                <a href="https://interaktif.fakta.com/"
                                    class="item" style="color:var(--color-red);">
                                    Interaktif
                                </a>
                                <a href="{{route('webinar')}}"
                                    class="item {{request()->is('webinar') ? 'link-active' : ''}}">
                                    Program
                                </a>
                                <a href="{{route('category.detail', 'pointer')}}"
                                    class="item {{request()->is('category/pointer') ? 'link-active' : ''}}" style="color:var(--color-red);">
                                    Pointer
                                </a>
                                @if(isset($categoryData['listCategory']) && count($categoryData['listCategory']) > 0)
                                    @foreach ($categoryData['listCategory'] as $cat )
                                        @if($cat->slug != "data" && $cat->slug != "pointer")
                                            @if ($cat->slug == "whistler")
                                                <a href="#"
                                                    class="item {{request()->is('category/' . $cat->slug) ? 'link-active' : ''}}">
                                                    {{$cat->title}}
                                                </a>
                                            @else
                                                <a href="{{route('category.detail', $cat->slug)}}"
                                                    class="item {{request()->is('category/' . $cat->slug) ? 'link-active' : ''}}">
                                                    {{$cat->title}}
                                                </a>
                                            @endif
                                        @endif
                                    @endforeach    
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="fkt-flash-news-wrapper">
                    <div class="fkt-flash-news-container">
                        <span>Focus</span>
                        <marquee behavior="" direction="">
                            <ul class="fkt-flash-news-list">
                                @if (isset($listFlashNews) && $listFlashNews->count() > 0)
                                @foreach ($listFlashNews as $item)
                                <li class="fkt-flash-news-list-item"><a href="{{route('news.detail', ['category_slug' => $item->category_slug, 'slug' => $item->slug])}}" class="fkt-white-links" > <b>{!!html_entity_decode($item->title)!!}</b>
                                </a></li>
                                @endforeach
                                @else
                                <li class="fkt-flash-news-list-item"> Flash News Not Found</li>
                                @endif
                            </ul>
                        </marquee>
                    </div>
                </div>
            </div>            
            <div class="container" id="krp-content-wrapper">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer>
                <div class="krp-footer-logo">
                    <img src="{{asset('assets/img/logo-fakta.png')}}" alt="">
                </div>
                <div class="krp-footer-bottom">
                    <div class="fkt-footer-affiliate-media">
                        <div class="fkt-footer-affiliate-media-text">
                            Affiliate Media:
                        </div>
                        <div class="fkt-footer-affiliate-media-logo" style="background-image: url({{asset('assets/img/logo-korporat.png')}})">
                        </div>
                        <div class="fkt-footer-affiliate-media-logo" style="background-image: url({{asset('assets/img/logo-gawang.png')}})">
                        </div>
                        <div class="fkt-footer-affiliate-media-logo" style="background-image: url({{asset('assets/img/logo-otospin.png')}})">
                        </div>
                        <div class="fkt-footer-affiliate-media-logo" style="background-image: url({{asset('assets/img/logo-kutai.png')}})">
                        </div>
                        <div class="fkt-footer-affiliate-media-logo" style="background-image: url({{asset('assets/img/logo-uhye.png')}})">
                        </div>
                        <div class="fkt-footer-affiliate-media-logo" style="background-image: url({{asset('assets/img/logo-wartawati.png')}})">
                        </div>
                    </div>
                    <div class="krp-footer-download">
                        <small>Download Fakta Application:</small>
                        <div class="krp-footer-store">
                            <a href="https://www.apple.com/id/app-store/" target="_blank"><img src="{{ asset('assets/images/icon/appstore.png') }}" alt=""></a>
                            <a href="https://play.google.com/" target="_blank"><img src="{{ asset('assets/images/icon/playstore.png') }}" alt=""></a>
                        </div>
                    </div>
                    <div class="krp-footer-socmed">
                        <em style="font-style: normal;">Follow Us</em>
                        <div class="krp-footer-socmed-content">
                            <a href="https://www.instagram.com/faktacom_" target="_blank"><img src="{{ asset('assets/images/icon/instagram.png') }}" alt=""></a>
                            <a href="https://twitter.com/faktacom_" target="_blank"><img src="{{ asset('assets/images/icon/twitter.png') }}" alt=""></a>
                            <a href="https://www.facebook.com/faktadotcom" target="_blank"><img src="{{ asset('assets/images/icon/facebook.png') }}" alt=""></a>
                            <a href="https://www.linkedin.com/company/faktacom/" target="_blank"><img src="{{ asset('assets/images/icon/footer-linkedin.png') }}" alt=""></a>
                            <a href="https://www.youtube.com/@Faktacom_" target="_blank"><img src="{{ asset('assets/images/icon/footer-youtube.png') }}" alt=""></a>
                        </div>
                    </div>
                    <div class="krp-footer-content">
                        <div class="krp-footer-content-about">
                            @php
                            $separator = "";
                            @endphp
                            @foreach (\App\Models\Admin\ListFooterLink::orderBy('order_footer', 'asc')->get() as $footer)
                            @if ($footer->link_type_id == 1)
                            <small><a href="{{$footer->link}}" target="_blank">{{$separator .
                                    ($footer->title)}}</a></small>
                            @elseif ($footer->link_type_id == 7)
                            <small><a href="{{route('category.detail', $footer->slug)}}">{{$separator .
                                    ($footer->title)}}</a></small>
                            @else
                            <small><a href="{{route('footer.detail', $footer->slug)}}">{{$separator .
                                    ($footer->title)}}</a></small>
                            @endif
                            @php
                            $separator = "| ";
                            @endphp
                            @endforeach
                        </div>
                    </div>
                    <div class="fkt-newsletter-container-mobile">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('invalid'))
                            <div class="alert alert-danger">
                                {{ session('invalid') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="fkt-newsletter-label">Newsletter</div>
                        <form class="fkt-newsletter-form" action="{{ route('newsletter') }}" method="post" >
                            @csrf
                            <input type="text" class="fkt-newsletter-input" placeholder="Email Address" name="newsletter_email">
                            <button class="fkt-newsletter-button"><i class="fa fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>

                <div class="krp-footer-top">
                    <div class="container" id="krp-footer-container">
                        <div class="krp-footer-top-desktop">
                            <div class="krp-footer-download-desktop">
                                <small>Download aplikasi Korporat:</small>
                                <div class="krp-footer-store">
                                    <a href="https://www.apple.com/id/app-store/" target="_blank"><img src="{{ asset('assets/images/icon/appstore.png') }}" alt=""></a>
                                    <a href="https://play.google.com/" target="_blank"><img src="{{ asset('assets/images/icon/playstore.png') }}" alt=""></a>
                                </div>
                            </div>
                            <div class="krp-footer-socmed-desktop">
                                <div class="krp-footer-socmed-content">
                                    <a href="https://www.youtube.com/@Faktacom_" target="_blank"><img src="{{ asset('assets/images/icon/youtube.png') }}" alt=""></a>
                                    <a href="https://www.instagram.com/faktacom_" target="_blank"><img src="{{ asset('assets/images/icon/instagram.png') }}" alt=""></a>
                                    <a href="https://twitter.com/faktacom_" target="_blank"><img src="{{ asset('assets/images/icon/twitter.png') }}" alt=""></a>
                                    <a href="https://www.facebook.com/faktadotcom" target="_blank"><img src="{{ asset('assets/images/icon/facebook.png') }}" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container" id="krp-footer-container">
                    <div class="krp-footer-bot-desktop">
                        <div class="krp-footer-content-about">
                            <div class="krp-footer-logo-desktop">
                                <img src="{{asset('assets/img/logo-fakta.png')}}" alt="">
                            </div>
                            <p style="margin: 0; font-size:0.95rem;">Affiliate Media:</p>
                            <div class="fkt-footer-affiliate-media-desktop">
                                    <div class="fkt-footer-affiliate-media-desktop-logo" style="background-image: url({{asset('assets/img/logo-korporat.png')}})">
                                    </div>
                                    <div class="fkt-footer-affiliate-media-desktop-logo" style="background-image: url({{asset('assets/img/logo-gawang.png')}})">
                                    </div>
                                    <div class="fkt-footer-affiliate-media-desktop-logo" style="background-image: url({{asset('assets/img/logo-otospin.png')}})">
                                    </div>
                            </div>
                            <div class="fkt-footer-affiliate-media-desktop">
                                    <div class="fkt-footer-affiliate-media-desktop-logo" style="background-image: url({{asset('assets/img/logo-kutai.png')}})">
                                    </div>
                                    <div class="fkt-footer-affiliate-media-desktop-logo" style="background-image: url({{asset('assets/img/logo-uhye.png')}})">
                                    </div>
                                    <div class="fkt-footer-affiliate-media-desktop-logo" style="background-image: url({{asset('assets/img/logo-wartawati.png')}})">
                                    </div>
                            </div>
                        </div>
                        <div class="fkt-footer-link-container">
                            <div class="fkt-footer-footer-link">
                                @php
                                $separator = "";
                                $row = 1;
                                @endphp
                                @foreach (\App\Models\Admin\ListFooterLink::orderBy('order_footer', 'asc')->where('column_footer',
                                1)->get() as
                                $footer)
                                @if ($footer->link_type_id == 1)
                                <small style="grid-column: 1; grid-row:{{$row}};" ;><b><a href="{{$footer->link}}" target="_blank">{{ $separator .
                                        ($footer->title)}}</a></b></small>
                                @elseif ($footer->link_type_id == 7)
                                <small style="grid-column: 1; grid-row:{{$row}};"><b><a href="{{route('category.detail', $footer->slug)}}">{{ $separator .
                                                    ($footer->title)}}</a></b></small>
                                @else
                                <small style="grid-column: 1; grid-row:{{$row}};"><b><a href="{{route('footer.detail', $footer->slug)}}">{{ $separator .
                                                    ($footer->title)}}</a></b></small>
                                @endif
                                @php $row++; @endphp
                                @endforeach
                                @php
                                $separator = "";
                                $row = 1;
                                @endphp
                                @foreach (\App\Models\Admin\ListFooterLink::orderBy('order_footer', 'asc')->where('column_footer',
                                2)->get() as
                                $footer)
                                @if ($footer->link_type_id == 1)
                                <small style="grid-column: 2; grid-row:{{$row}};"><b><a href="{{$footer->link}}" target="_blank">{{ $separator .
                                        ($footer->title)}}</a></b></small>
                                @elseif ($footer->link_type_id == 7)
                                <small style="grid-column: 2; grid-row:{{$row}};"><b><a href="{{route('category.detail', $footer->slug)}}">{{ $separator .
                                                    ($footer->title)}}</a></b></small>
                                @else
                                <small style="grid-column: 2; grid-row:{{$row}};"><b><a href="{{route('footer.detail', $footer->slug)}}">{{ $separator .
                                                    ($footer->title)}}</a></b></small>
                                @endif
                                @php $row++; @endphp
                                @endforeach
                                <small style="grid-column: 2; grid-row:2;"><b><a href="{{route('survey')}}">Survey</a></b></small>
                            </div>
                            <small style="display: grid; justify-items:center;">&copy; Fakta.com {{ date('Y') }}</small>
                        </div>
                        
                        <div class="fkt-newsletter-container">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if(session('invalid'))
                                <div class="alert alert-danger">
                                    {{ session('invalid') }}
                                </div>
                            @endif
                            @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">
                                    {{ $error }}
                                </div>
                            @endforeach
                            @endif
                            <div class="fkt-newsletter-label">Newsletter</div>
                            <form class="fkt-newsletter-form" action="{{ route('newsletter') }}" method="post" >
                                @csrf
                                <input type="text" class="fkt-newsletter-input" placeholder="Email Address" name="newsletter_email">
                                <button class="fkt-newsletter-button"><i class="fa fa-paper-plane"></i></button>
                            </form>
                            <div class="fkt-footer-apps-desktop">
                                <p>Download Fakta Application:</p>
                                <div class="fkt-footer-apps-desktop-item">
                                    <a href="https://www.apple.com/id/app-store/" target="_blank"><div class="fkt-footer-apps-desktop-img" style="background-image: url({{ asset('assets/images/icon/appstore.png') }})"></div></a>
                                    <a href="https://play.google.com/" target="_blank"><div class="fkt-footer-apps-desktop-img" style="background-image: url({{ asset('assets/images/icon/playstore.png') }})"></div></a>
                                </div>
                            </div>
                            <div class="krp-footer-socmed-content">
                                <a href="https://www.instagram.com/faktacom_" target="_blank"><img src="{{ asset('assets/images/icon/instagram.png') }}" alt=""></a>
                                <a href="https://twitter.com/faktacom_" target="_blank"><img src="{{ asset('assets/images/icon/twitter.png') }}" alt=""></a>
                                <a href="https://www.facebook.com/faktadotcom" target="_blank"><img src="{{ asset('assets/images/icon/facebook.png') }}" alt=""></a>
                                <a href="https://www.linkedin.com/company/faktacom/" target="_blank"><img src="{{ asset('assets/images/icon/footer-linkedin.png') }}" alt=""></a>
                                <a href="https://www.youtube.com/@Faktacom_" target="_blank"><img src="{{ asset('assets/images/icon/footer-youtube.png') }}" alt=""></a>          
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
        </div>
        </footer>
        <div class="krp-footer-copyright">
            <small>&copy; Fakta.com {{ date('Y') }}</small>
        </div>
       
        </div>

        <!-- End Footer -->
    </main>
    
    @if (isset($detailWebinar) && $detailWebinar->count() > 0)
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel">{{$detailWebinar[0]->title}}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="webinarId" name="webinar_id" value="{{ $detailWebinar[0]->webinar_id }}">
                <input type="hidden" id="webinarSlug" name="slug" value="{{ $detailWebinar[0]->slug }}">
                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input name="full_name" placeholder="Full Name..." class="form-control input-error" id="fullName" type="text">
                </div>
                <div class="form-group">
                    <label for="job">Pekerjaan</label>
                    <input name="job" placeholder="Job..." class="form-control input-error" id="job" type="text">
                </div>
                <div class="form-group">
                    <label for="email_paricipant">Email</label>
                    <input name="email_paricipant" placeholder="Email..." class="form-control input-error" id="emailParticipant" type="email">
                </div>
                <div class="form-group">
                    <label for="no_telepon">No Telepon</label>
                    <input name="no_telepon" placeholder="No Telepon..." class="form-control input-error" id="noTelepon" type="text">
                </div>
                <div class="form-group">
                    <label for="provinceId">Provinsi</label>
                    @if (isset($listProvince) && !empty($listProvince))
                        <select name="provinceId" id="provinceId" class="form-control input-error" onchange="getCity(this.value)">
                            @foreach ($listProvince as $item)
                                <option value="{{$item->province_id}}">{{$item->province_name}}</option>
                            @endforeach
                        </select>
                    @else
                        <h6>Data province not found.</h6>
                    @endif
                </div>
                <div class="form-group">
                    <label for="cityId">Kota</label>
                    <select name="cityId" id="cityId" class="form-control input-error" disabled>
                        <option value="">--Pilih Provinsi--</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn" style="background-color: var(--color-red); color:var(--color-white)" onclick="postRegisterWebinar()">Daftar</button>
            </div>
          </div>
        </div>
      </div>
    @endif
    <div class="fkt-navbar-mobile-wrapper">
        <div class="fkt-navbar-mobile-container">
            <a href="{{route('category.detail', 'infografis')}}">
                <div class="fkt-navbar-mobile-item">
                    <img src="{{ asset('assets/images/category-icon/1673257522_1673249511_infografis.png') }}" alt="" width="24">
                    <span>Infografik</span>
                </div>
            </a>
            <a href="{{route('category.detail', 'data')}}">
                <div class="fkt-navbar-mobile-item">
                    <img src="{{ asset('assets/images/icon/data.png') }}" alt="" width="24">
                    <span>Data</span>
                </div>
            </a>
            <a href="https://interaktif.fakta.com/">
                <div class="fkt-navbar-mobile-item">
                    <img src="{{ asset('assets/images/icon/interactive.png') }}" alt="" width="24">
                    <span>Interaktif</span>
                </div>
            </a>
            <a href="{{route('webinar')}}">
                <div class="fkt-navbar-mobile-item">
                    <img src="{{ asset('assets/images/icon/webinar.png') }}" alt="" width="24">
                    <span>Program</span>
                </div>
            </a>
            <a href="/maintenance">
                <div class="fkt-navbar-mobile-item">
                    <img src="{{ asset('assets/images/icon/job.png') }}" alt="" width="24">
                    <span>Jobs</span>
                </div>
            </a>
        </div>
    </div>
</body>

</html>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js" integrity="sha512-jNDtFf7qgU0eH/+Z42FG4fw3w7DM/9zbgNPe3wfJlCylVDTT3IgKW5r92Vy9IHa6U50vyMz5gRByIu4YIXFtaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
<!--<script> -->
<!--    $(document).ready(function(){-->
<!--        $item->image.lazyload();-->
<!--    });-->
<!--</script>-->
<script type="text/javascript" src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{asset('assets/js/jquery-3.6.0.js?v=2.1')}}"></script>
        <script src="{{asset('assets/js/jquery.min.js?v=2.1')}}"></script>
        <script src="{{asset('assets/js/owl.carousel.min.js?v=2.1')}}"></script>
        <script src="{{asset('assets/js/bootstrap.bundle.min.js?v=2.1')}}"></script>
        <script src="{{asset('assets/js/script.js?v=2.1')}}"></script>
        <script src="{{asset('assets/js/siema.min.js?v=2.1')}}"></script>
        <script src="{{asset('assets/js/loadPage.js?v=2.1')}}"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        @stack('js')
        <script>
            const scrollDiv = document.getElementById('scrollDiv');
            var adsElement = [];
            adsElement.push(document.getElementById('adsParallax'));
            adsElement.push(document.getElementById('adsBody1'));
            adsElement.push(document.getElementById('adsBody2'));
            adsElement.push(document.getElementById('adsBody3'));
            adsElement.push(document.getElementById('adsVerticalLeft'));
            adsElement.push(document.getElementById('adsVerticalRight'));

            function replaceMissingImage(target, image){
                target.onerror = null;
                target.src = `{{asset('${image}')}}`;
            }

            scrollDiv.addEventListener('scroll', detectAds);

            function toggleSidebar() {
                document.getElementById("mobileSidebar").classList.toggle("expanded");
            }

            $('.dark-mod').on('click', function () {
                if(localStorage.getItem('mode') === 'dark'){
                    localStorage.setItem('mode', 'white'); 
                    $('body').removeClass('nightview');
                }else{
                    localStorage.setItem('mode', 'dark'); 
                    $('body').addClass('nightview');
                }
                return false;
            });

            if(localStorage.getItem('mode') === 'dark'){   
                $('body').addClass('nightview');
            }else{
                $('body').removeClass('nightview');
            }
    
            function getLinkAds(adsId) {
                $.ajax({
                    url: '{{route("ads.click")}}',
                    method: 'POST',
                    dataType: "json",
                    data: {
                        adsId: adsId,
                        _token: "{{csrf_token()}}"
                    },

                    success: function(res) {
                        if (res.bool == true) {
                            linkUrl = res.data;
                            if(!linkUrl.includes("https://")){
                                linkUrl = "https://" + res.data;
                            }
                            window.open(linkUrl, '_blank');
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }

            function addViewAds(adsSlotId) {
                // $.ajax({
                //     url: '{{route("ads.view")}}',
                //     method: 'POST',
                //     dataType: "json",
                //     data: {
                //         adsSlotId: adsSlotId,
                //         _token: "{{csrf_token()}}"
                //     },

                //     success: function(res) {
                //         console.log('success');
                //     },
                //     error: function(error) {
                //         console.log(error);
                //     }
                // });
            }

            function detectAds() {
                for (let index = 0; index < adsElement.length; index++) {
                    if (adsElement[index]) {
                        if (adsElement[index].getBoundingClientRect().top >= 0 && adsElement[index].getBoundingClientRect().bottom <= window.innerHeight) {
                            addViewAds(index + 1);
                            removeAdsElement(index);
                        }
                        if (index == 0 || index == 4 || index == 5) {
                            addViewAds(index + 1);
                            removeAdsElement(index);
                        }
                    }
                }
            }

            function removeAdsElement(index) {
                adsElement.splice(index, 1, null);
            }

            function getCity(provinceId){
                var cityDropdown = document.getElementById("cityId");
                $.ajax({
                    url:"{{route('survey.city')}}",
                    method: "POST",
                    dataType: "json",
                    data:{
                        province_id:provinceId,
                        ajax:true,
                        _token: "{{csrf_token()}}"
                    },
                    success:function(result){
                        var valid = result.bool;
                        var cityData = result.data;
                        if(valid && cityData != ""){
                            cityDropdown.disabled = false;
                            cityDropdown.innerHTML = "";
                            for(let i = 0; i < cityData.length;i++){
                                let cityOption = document.createElement("option");
                                cityOption.value = cityData[i].city_id;
                                cityOption.text = cityData[i].city_name;
                                cityDropdown.add(cityOption);
                            }
                        }else{
                            console.log("City not found")
                        }
                    },
                    error: function(error){
                        console.log(error);
                    }

                })
            }

        </script>
        <script>
            var sideParentMenu = document.getElementsByClassName("krp-sidebar-menu fkt-sidebar-parent-menu");
            var i;
            var active = 0;
            for(i = 0; i < sideParentMenu.length; i++){
                sideParentMenu[i].addEventListener("mouseover", function() {
                    var activeMenu = document.querySelector(".menu-active");
                    if (activeMenu) {
                        activeMenu.classList.remove("menu-active");
                        activeMenu.nextElementSibling.style.display = "none";
                    }
                    if(active == 0){
                        this.classList.add("menu-active");
                        var container = this.nextElementSibling;
                        container.style.display = "block";
                        active = 1;
                    } else{
                        var container = this.nextElementSibling;
                        container.style.display = "none";
                        this.classList.remove("menu-active");
                        active = 0;
                    }
                    
                });
            }
        </script>
        <script>
                $('#nav-scroll').owlCarousel({
                    items:1,
                    loop: true,
                    margin: 15,
                    nav: true,
                });
        </script>