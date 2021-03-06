<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ URL::asset('assets/images/favicon.ico') }}" type="image/x-icon"/>
    <title>{{$title}}</title>

    <!-- jQuery -->
    <script src="{{asset('assets/js/jquery/dist/jquery.min.js')}}"></script>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('assets/css/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- This is a Custom CSS -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <!-- This is a colors CSS -->
    <link href="{{asset('assets/css/colors/default.css')}}" id="theme" rel="stylesheet">
    <link href="{{asset('assets/js/dualbox/dist/bootstrap-duallistbox.min.css')}}" id="theme" rel="stylesheet">

    {{--custom style--}}
    @stack('head')
</head>

<body class="fix-sidebar">

<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top m-b-0">
        <div class="navbar-header">
            <div class="top-left-part">
                <a class="logo" href="/admin">
                    {{--                    add image for logo --}}
                </a>
            </div>

            <ul class="nav navbar-top-links navbar-left">
                <li>
                    <a href="javascript:void(0)" class="open-close waves-effect waves-light visible-xs"><i
                            class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <ul class="nav navbar-top-links navbar-right pull-right">
                <li class="dropdown">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                        <b class="hidden-xs">{{Auth::user()->name}}</b>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated flipInY">
                        <li>
                            <a href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i>Դուրս Գալ
                            </a>
                        </li>
                        <form id="logout-form" action="/logout" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav slimscrollsidebar">
            <div class="sidebar-head">
                <h3>
                    <span class="fa-fw open-close">
                        <i class="fas fa-align-justify hidden-xs"></i>
                        <i class="fas fa-times visible-xs"></i>
                    </span>
                    <span class="hide-menu">Մենյու</span>
                </h3>
            </div>

            <ul class="nav" id="side-menu">

                <li class="devider"></li>
                <li><a href="/clients" data-route="{{ app('router')->getRoutes()->match(app('request')->create('/clients'))->getName() }}" class="waves-effect"><i class="mdi mdi-account fa-fw"></i>
                        <span class="hide-menu">Հաճախորդներ</span></a>
                </li>

                <li><a href="/employees" data-route="{{ app('router')->getRoutes()->match(app('request')->create('/employees'))->getName() }}" class="waves-effect"><i class="mdi mdi-worker fa-fw"></i>
                        <span class="hide-menu">Աշխատակիցներ</span></a>
                </li>

                <li><a href="/staffs" data-route="{{ app('router')->getRoutes()->match(app('request')->create('/staffs'))->getName() }}" class="waves-effect"><i class="mdi mdi-account-settings-variant fa-fw"></i>
                        <span class="hide-menu">Անձնակազմ</span></a>
                </li>

                <li><a href="/materials" data-route="{{ app('router')->getRoutes()->match(app('request')->create('/materials'))->getName() }}" class="waves-effect"><i class="mdi mdi-material-ui fa-fw"></i>
                        <span class="hide-menu">Ապրանքներ</span></a>
                </li>

                <li><a href="/material-list" data-route="{{ app('router')->getRoutes()->match(app('request')->create('/material-list'))->getName() }}" class="waves-effect"><i class="mdi mdi-numeric fa-fw"></i>
                        <span class="hide-menu">Ապրանքների Մուտք</span></a>
                </li>

                <li><a href="/orders" data-route="{{ app('router')->getRoutes()->match(app('request')->create('/orders'))->getName() }}" class="waves-effect"><i class="mdi mdi-coin fa-fw"></i>
                        <span class="hide-menu">Գովազդի Պատվերներ</span></a>
                </li>

{{--                <li><a href="/services" class="waves-effect"><i class="mdi mdi-server fa-fw"></i>--}}
{{--                        <span class="hide-menu">Ծառայություններ</span></a>--}}
{{--                </li>--}}

                <li><a href="/cars" data-route="{{ app('router')->getRoutes()->match(app('request')->create('/cars'))->getName() }}" class="waves-effect"><i class="mdi mdi-car-wash fa-fw"></i>
                        <span class="hide-menu">Ավտոաշտարակներ</span></a>
                </li>

                <li><a href="/drivers" data-route="{{ app('router')->getRoutes()->match(app('request')->create('/drivers'))->getName() }}" class="waves-effect"><i class="mdi mdi-library fa-fw"></i>
                        <span class="hide-menu">Վարորդներ</span></a>
                </li>

                <li><a href="/crane-orders" data-route="{{ app('router')->getRoutes()->match(app('request')->create('/crane-orders'))->getName() }}" class="waves-effect"><i class="mdi mdi-car fa-fw"></i>
                        <span class="hide-menu">Ավտոաշտարակի Պատվերներ</span></a>
                </li>

                <li><a href="/cashdesk" data-route="{{ app('router')->getRoutes()->match(app('request')->create('/cashdesk'))->getName() }}" class="waves-effect"><i class="mdi mdi-cash fa-fw"></i>
                        <span class="hide-menu">Դրամարկղ</span></a>
                </li>

                <li><a href="/spendings" data-route="{{ app('router')->getRoutes()->match(app('request')->create('/spendings'))->getName() }}" class="waves-effect"><i class="mdi mdi-coins fa-fw"></i>
                        <span class="hide-menu">Այլ Ծախսեր</span></a>
                </li>

            </ul>
        </div>
    </div>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">{{$title}}</h4>
                </div>
            </div>

            <!-- .row -->
            <main class="py-4">
                @yield('content')
            </main>
            <!-- .row -->

        </div>
        <footer class="footer text-center"> 2020 &copy; Իրագործումը ՝ Պարգև Աղաբեկյանի</footer>
    </div>
</div>

</body>
<!-- Bootstrap Core JavaScript -->
<script src="{{asset('assets/css/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Sidebar menu plugin JavaScript -->
<script src="{{asset('assets/js/sidebar-nav/dist/sidebar-nav.min.js')}}"></script>
<!--Slimscroll JavaScript For custom scroll-->
{{--<script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>--}}
<!--Wave Effects -->
<script src="{{asset('assets/js/waves.js')}}"></script>
<!-- Custom Theme JavaScript min -->
<script src="{{asset('assets/js/custom.min.js')}}"></script>
<script src="{{asset('assets/js/dualbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>

{{--custom script--}}
@stack('foot')
<script>
    @isset($whitelist_routes)
        $(document).ready(function () {
            let routes = {!! $whitelist_routes ?? [] !!};
            console.log(routes)
            $("#side-menu a").each(function () {
                let route = $(this).attr('data-route');
                let data = routes.find(e => e.route == route);
                if (data === undefined) {
                    $(this).parent().remove();
                }
            });
            $(".white-box a, .white-box form, .white-box button").each(function() {
                let route = $(this).attr('data-route');
                if(route !== undefined) {
                    console.log(route)
                    let data = routes.find(e => e.route == route);
                    if (data == undefined) {
                        $(this).remove();
                    }
                }
            });
        });
    @endisset
</script>
</html>
