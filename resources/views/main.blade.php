<!DOCTYPE html>
<html>
<head>
    <title>Main View</title>
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
            <div class="row border-bottom white-bg">
            <nav class="navbar navbar-static-top" role="navigation">
                <div class="navbar-header">
                    <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                        <i class="fa fa-reorder"></i>
                    </button>
                    <a href="#" class="navbar-brand">Quang Đại</a>
                </div>
                <div class="navbar-collapse collapse" id="navbar">
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a aria-expanded="false" role="button" href="{{ route('accounts.index') }}"> Quản lý Accounts</a>
                        </li>
                        <li class="dropdown">
                            <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="">Menu item</a></li>
                                <li><a href="">Menu item</a></li>
                                <li><a href="">Menu item</a></li>
                                <li><a href="">Menu item</a></li>
                            </ul>
                        </li>
    
                    </ul>
                    <ul class="nav navbar-top-links navbar-right">
                        
                            @if(Auth::check())
                                <li>Welcome, {{ Auth::user()->name }}</li>
                                <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i>Logout</a></li>
                            @else
                                <li><a href="{{ route('login') }}"><i class="fa fa-sign-out"></i>Login</a></li>
                            @endif
                    </ul>
                </div>
            </nav>
            </div>
            <div class="wrapper wrapper-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box-list-clone">
                                <label class="lable_item lable_live_count"> Total 
                                    <span class="live_count" id="live_count">0</span>
                                    <button class="btn-live-count float-end">Copy</button>
                                </label>
                                <textarea name="usernames" id="usernames" rows="14" class="form-control form-textarea"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-sm-9 m-b-xs">
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group"><input type="checkbox" id="select-all" class="input-sm "> <span class="input-group-btn">
                                        <button id="checkButton" class="btn btn-sm btn-primary">Check</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="lable_item lable_live_count"> LIVE 
                                        <span class="live_count" id="live_count">0</span>
                                        <button class="btn-die-count btn-sm float-end">Copy</button>
                                    </label>
                                    <textarea name="clone_live" id="clone_live" rows="8" class="form-control form-textarea"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="lable_item lable_die_count"> LOCK 
                                        <span class="die_count" id="die_count">0</span>
                                        <button class="btn-die-count btn-sm float-end">Copy</button>
                                    </label>
                                    <textarea name="clone_die" id="clone_lock" rows="8" class="form-control form-textarea"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="lable_item lable_die_count text-info"> SUPPEND 
                                        <span class="die_count" id="die_count">0</span>
                                        <button class="btn-die-count btn-sm float-end">Copy</button>
                                    </label>
                                    <textarea name="clone_error" id="clone_suppend" rows="8" class="form-control form-textarea"></textarea>
                                </div>
                                
                            </div>

                        </div>
    
                    </div>
    
                </div>
    
            </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/custom.js"></script>
    
</body>
</html>
