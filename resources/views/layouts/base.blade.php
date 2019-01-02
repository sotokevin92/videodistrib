<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('custom.titulo') }}</title>

    <!-- Bootstrap Core CSS -->
    <link href={{ asset("css/bootstrap.min.css") }} rel="stylesheet">
    <link href={{ asset("css/font-awesome.min.css") }} rel="stylesheet">
    <link href={{ asset("css/simple-sidebar.css") }} rel="stylesheet">

    <link href={{ asset("css/videodistrib.css") }} rel="stylesheet">
    @yield('estilos')

    <!-- jQuery en cabecera para poder cargar scripts a mitad del template -->
    <script src="{{ asset('js/jquery.js') }}"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            @include('sidebars.default')
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <h2>
                @yield('titulo')
            </h2>
            <hr>
            <br>
            @yield('contenido')
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap Core JavaScript -->
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    </script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('js/videodistrib.js') }}"></script>
    @yield('scripts')

    <!-- Menu Toggle Script -->
    {{-- <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script> --}}

</body>

</html>
