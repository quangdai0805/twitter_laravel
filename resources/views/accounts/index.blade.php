<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Dashboard v.4</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
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
                <a href="{{ route('main') }}" class="navbar-brand">Home</a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a aria-expanded="false" role="button" href="layouts.html"> Back to main Layout page</a>
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
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                            <li><a href="">Menu item</a></li>
                        </ul>
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
                        <a href="login.html">
                            <i class="fa fa-sign-out"></i> Log out
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
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Custom responsive table </h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="">
                                        <i class="fa fa-wrench"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-user">
                                        <li><a href="#">Config option 1</a>
                                        </li>
                                        <li><a href="#">Config option 2</a>
                                        </li>
                                    </ul>
                                    <a class="">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            
                            {{-- Start Todo-List --}}
{{-- 
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Small todo list</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                        <a class="close-link">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <ul class="todo-list m-t small-list">
                                        <li>
                                            <a href="#" class="check-link"><i class="fa fa-check-square"></i> </a>
                                            <span class="m-l-xs todo-completed">Buy a milk</span>

                                        </li>
                                        <li>
                                            <a href="#" class="check-link"><i class="fa fa-square-o"></i> </a>
                                            <span class="m-l-xs">Go to shop and find some products.</span>

                                        </li>
                                        <li>
                                            <a href="#" class="check-link"><i class="fa fa-square-o"></i> </a>
                                            <span class="m-l-xs">Send documents to Mike</span>
                                            <small class="label label-primary"><i class="fa fa-clock-o"></i> 1 mins</small>
                                        </li>
                                    </ul>
                                </div>
                            </div> 
                            --}}

                            {{-- End Todo List --}}
                            <div class="ibox-content">
                                
                                <div class="table-responsive">
                                    <form method="POST" action="{{ route('deleteSelected') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger mb-3">Delete Selected</button>

                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select-all"></th>
                                                <th>#</th>
                                                <th>User Name</th>
                                                <th>Proxy </th>
                                                <th>Follower </th>
                                                <th>Following </th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($accounts as $account)
                                                    <tr>
                                                        <td><input type="checkbox" name="selected_accounts[]" value="{{ $account->id }}"></td>
                                                        <td>{{ $account->id }}</td>
                                                        <td>{{ $account->username }}</td>
                                                        <td>{{ $account->proxy }}</td>
                                                        <td>{{ $account->follower }}</td>
                                                        <td>{{ $account->following }}</td>
                                                        <td>{{ $account->status }}</td>
                                                        <td>
                                                            <!-- Add actions like edit, delete if needed -->
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </form>

                                   
                                </div>

                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>Action Accounts</h5>
                                        <div class="ibox-tools">
                                            <a class="collapse-link">
                                                <i class="fa fa-chevron-up"></i>
                                            </a>
                                            <a class="close-link">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="ibox-content">
                                        <ul class="todo-list m-t small-list">
                                            <li>
                                                <button type="button" class="btn btn-sm btn-primary"> Login</button> 
                                            </li>
                                            <li>
                                                <div class="input-group col-sm-6">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-sm btn-primary"> Follow</button> 
                                                    </span>

                                                    <input type="text" placeholder="UID" class="input-sm form-control">
                                                </div>
                                        
    
                                            </li>
                                            <li>
                                                <div class="input-group col-sm-6">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-sm btn-primary"> Tweet</button> 
                                                    </span>

                                                    <input type="text" placeholder="UID" class="input-sm form-control">
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div> 


                                {{-- <div class="row">

                                    <div class="col-sm-9 m-b-xs">
                                        <div data-toggle="buttons" class="btn-group">
                                            <label class="btn btn-sm btn-white"> <input type="radio" id="option1" name="options"> Day </label>
                                            <label class="btn btn-sm btn-white active"> <input type="radio" id="option2" name="options"> Week </label>
                                            <label class="btn btn-sm btn-white"> <input type="radio" id="option3" name="options"> Month </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" placeholder="Search" class="input-sm form-control">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-sm btn-primary"> Go!</button> 
                                            </span>
                                        </div>
                                    </div>
                                </div> --}}

                            </div>
                        </div>

                        <form method="POST" class="m-t" role="form" action="{{ route('accounts.store') }}" >
                            @csrf
                            <div class="box-list-clone">
                                <label class="lable_item lable_live_count"> Total 
                                    <span class="live_count" id="live_count">0</span>
                                    <button type="submit" class="btn btn-sm btn-primary">Add</button>
                                </label>
                                <textarea name="accounts" id="accounts" rows="14" class="form-control form-textarea"></textarea>
                            </div>
                        </form>

                        
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
