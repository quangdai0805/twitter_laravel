<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <link href="css/bootstrap.min.css" rel="stylesheet">
     <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
     <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">
     <link href="css/animate.css" rel="stylesheet">
     <link href="css/style.css" rel="stylesheet">
</head>
<body class="top-navigation">
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
            @include('includes.header')
            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('js')
    {{-- <script src="js/custom.js"></script> --}}
    {{-- <script src="js/main.js"></script> --}}

</body>
</html>
