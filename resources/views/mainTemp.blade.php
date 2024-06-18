<!DOCTYPE html>
<html>
    <head>
        <title>Main View</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
         <link href="css/bootstrap.min.css" rel="stylesheet">
         <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
     
         <!-- Toastr style -->
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
                        <a aria-expanded="false" role="button" href="layouts.html"> Quản lý Accounts</a>
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
                    <li>
                        <a href="#">
                            <i class="fa fa-sign-out"></i> Log In
                        </a>
                    </li>
                    <li>
                        <a href="login.html">
                            <i class="fa fa-sign-out"></i> Log Out
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        </div>
        <div class="wrapper wrapper-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        {{-- <form method="POST" class="m-t" role="form" action="{{ route('accounts.store') }}" >
                            @csrf --}}
                            <div class="box-list-clone">
                                <label class="lable_item lable_live_count"> Total 
                                    <span class="live_count" id="live_count">0</span>
                                    <button type="submit" class="btn btn-sm btn-primary">Add</button>
                                </label>
                                <textarea name="accounts" id="accounts" rows="14" class="form-control form-textarea"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-sm-9 m-b-xs">
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group"><input type="checkbox" id="select-all" class="input-sm "> <span class="input-group-btn">
                                    <button id="checkButton" type="button" class="btn btn-sm btn-primary" style="margin-right: 0"> Check Accounts</button> </span></div>
                                </div>
                            </div>
                        {{-- </form> --}}

                        

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
                                    <span class="die_count" id="lock_count">0</span>
                                    <button class="btn-die-count btn-sm float-end">Copy</button>
                                </label>
                                <textarea name="clone_die" id="clone_lock" rows="8" class="form-control form-textarea"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="lable_item lable_die_count text-info"> SUPPEND 
                                    <span class="die_count" id="suppend_count">0</span>
                                    <button class="btn-die-count btn-sm float-end">Copy</button>
                                </label>
                                <textarea name="clone_error" id="clone_suppend" rows="8" class="form-control form-textarea"></textarea>
                            </div>
                        </div>
                        
                    </div>

                </div>

            </div>

        </div>
        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Example Company &copy; 2014-2017
            </div>
        </div>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- Flot -->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>

    <!-- ChartJS-->
    <script src="js/plugins/chartJs/Chart.min.js"></script>

    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <!-- Peity demo -->
    <script src="js/demo/peity-demo.js"></script>
    <script src="js/custom.js"></script>
    <script>
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.querySelectorAll('input[name="selected_accounts[]"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }
    </script>

</body>

</html>
