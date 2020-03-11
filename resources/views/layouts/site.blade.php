<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cilicia</title>
    <link rel="icon" href="{{ URL::asset('assets/images/favicon.ico') }}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ asset("assets/site/bootstrap/css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/site/style/stylev3.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/site/lightslider/dist/css/lightslider.min.css") }}">
    @stack('head')
</head>

<body>
    <div class="page-wrapper">

        <nav class="navbar navbar-expand-lg navbar-dark dark-bg mobile-menu fixed-top">
            <a class="navbar-brand" href="/">
                <img class="img-fluid logo-mobile" src="{{ asset("assets/site/images/logo.png") }}" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="/about-us">About Us</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="/academy-members">Members</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="/contact-us">Contact Us</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="/gallery">Gallery</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="header fixed-header" id="header">
            <div class="firstline">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5">
                            <nav class="navbar navbar-expand-lg navbar-dark dark-bg">
                                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                                    <div class="navbar-nav">
                                        <a class="nav-item nav-link" href="/">Home</a>
                                        <a class="nav-item nav-link" href="/about-us">About Us</a>
                                        <a class="nav-item nav-link" href="/academy-members">Members</a>
                                        <a class="nav-item nav-link" href="/gallery">Gallery</a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <div class="col-md-2">
                            <div class="logo-area">
                                <div class="logo-wrap">
                                    <a href="/">
                                        <img class="img-fluid logo" src="{{ asset("assets/site/images/logo.png") }}" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <nav class="navbar navbar-expand-lg  navbar-dark dark-bg">
                                <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                                    <div class="navbar-nav">
                                        <a class="nav-item nav-link active" href="#">Home <span class="sr-only">(current)</span></a>
                                        <a class="nav-item nav-link" href="#">Features</a>
                                        <a class="nav-item nav-link" href="#">Pricing</a>
                                        <a class="nav-item nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottomline">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 col-sm-6 submenu submenu-left">
                            <div class="left-menu justify-content-end pr-5">
                                <a href="/contact-us" class="mb-0 text-decoration-none text-white">Contact Us</a>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-0 main-submenu-wrapmiddle"></div>
                        <div class="col-md-5 col-sm-6 submenu submenu-right">
                            <div class="right-menu pl-5">
                                <a href="#" class="mb-0 text-decoration-none text-white">Shop</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            @yield("content")
            <div class="partners d-flex align-items-center position-relative">
                <div class="container">
                    <div class="row">
                        <ul id="partnerGallery">
                            <li>
                                <img src="{{ asset("assets/site/images/home/sponsor3.png") }}" />
                            </li>
                            <li>
                                <img src="{{ asset("assets/site/images/home/sponsor5.png") }}" />
                            </li>
                            <li>
                                <img src="{{ asset("assets/site/images/home/sponsor3.png") }}" />
                            </li>
                            <li>
                                <img src="{{ asset("assets/site/images/home/sponsor5.png") }}" />
                            </li>
                            <li>
                                <img src="{{ asset("assets/site/images/home/sponsor3.png") }}" />
                            </li>
                            <li>
                                <img src="{{ asset("assets/site/images/home/sponsor5.png") }}" />
                            </li>
                            <li>
                                <img src="{{ asset("assets/site/images/home/sponsor3.png") }}" />
                            </li>
                            <li>
                                <img src="{{ asset("assets/site/images/home/sponsor5.png") }}" />
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="up position-absolute text-center d-flex align-items-center justify-content-center">
                    <img style="height: 25px" src="{{ asset("assets/site/images/home/left-arrow.png") }}" alt="">
                </div>
            </div>
        </div>
        <div class="footer" style="background: url('{{ asset("assets/site/images/home/footer_bg.jpg") }}')">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <p class="section-title little"><img style="height: 15px" class="img-fluid" src="{{ asset("assets/site/images/ball-red.svg") }}" alt="">About Us</p>
                        <p class="text-white text-about">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolor ducimus inventore laboriosam laudantium quae ratione repellat sunt! Ab architecto aut blanditiis eligendi incidunt laboriosam maiores, molestias obcaecati quaerat repudiandae, totam?</p>
                    </div>
                    <div class="col-md-3 d-none d-md-block d-lg-block d-lg-block">
                        <p class="section-title little"><img style="height: 15px" class="img-fluid" src="{{ asset("assets/site/images/ball-red.svg") }}" alt="">Useful Links</p>
                        <ul class="d-inline-block left-list">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Gallery</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">My Account</a></li>
                        </ul>
                        <ul class="d-inline-block float-right">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Gallery</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">My Account</a></li>
                        </ul>
                    </div>
                    <div class="col-md-5 pl-lg-5">
                        <p class="section-title little"><img style="height: 15px" class="img-fluid" src="{{ asset("assets/site/images/ball-red.svg") }}" alt="">Contact Us</p>
                        <div class="icon-section">
                            <div class="block d-flex mb-3">
                                <p class="mr-1 left-block d-flex align-items-center justify-content-center right-borders">
                                    <img style="height: 15px" src="{{ asset("assets/site/images/home/phone-icon.svg") }}" alt="Phone Icon">
                                </p>
                                <p class="left-block d-flex align-items-center justify-content-center left-borders">+374 96 123 456</p>
                            </div>

                            <div class="block d-flex mb-3">
                                <p class="mr-1 left-block d-flex align-items-center justify-content-center right-borders">
                                    <img style="height: 15px" src="{{ asset("assets/site/images/home/email-icon.svg") }}" alt="Email Icon">
                                </p>
                                <p class="left-block d-flex align-items-center justify-content-center left-borders">info@cilicia.com</p>
                            </div>

                            <div class="block d-flex mb-3">
                                <p class="mr-1 left-block d-flex align-items-center justify-content-center right-borders">
                                    <img style="height: 15px" src="{{ asset("assets/site/images/home/map-icon.svg") }}" alt="Email Icon">
                                </p>
                                <p class="left-block d-flex align-items-center justify-content-center left-borders">Vardanants 3, Yerevan, Armenia 001</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <hr style="border-bottom: 1px solid #9c1d24">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset("assets/site/jquery/jquery.min.js") }}"></script>
<script src="{{ asset("assets/site/bootstrap/js/bootstrap.min.js") }}"></script>
<script src="{{ asset("assets/site/lightslider/dist/js/lightslider.min.js") }}"></script>
<script>
    window.onscroll = function() {myFunction()};

    var header = document.getElementById("header");
    var sticky = header.offsetTop;

    function myFunction() {
        if (window.pageYOffset > sticky + 50) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    }
    $(document).ready(function(){
        $('#partnerGallery').lightSlider({
            item:4,
            loop:false,
            slideMove:1,
            easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
            speed:600,
            pager: false,
            controls: false,
            responsive : [
                {
                    breakpoint:800,
                    settings: {
                        item:3,
                        slideMove:1,
                        slideMargin:6,
                    }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:2,
                        slideMove:1
                    }
                }
            ]
        });
        $(".up").click(function(){
            $("html, body").animate({ scrollTop: 0 }, "slow");
        });
    });
</script>
@stack('footer')

</html>
