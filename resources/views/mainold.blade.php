<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>INSPINIA | E-commerce</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">



</head>

<body>

<div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">

    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" action="search_results.html">
                        <div class="form-group">
                            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">Welcome to INSPINIA+ Admin Theme.</span>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                        </a>
                        <ul class="dropdown-menu dropdown-messages">
                            <li>
                                <div class="dropdown-messages-box">
                                    <a href="profile.html" class="pull-left">
                                        <img alt="image" class="img-circle" src="img/a7.jpg">
                                    </a>
                                    <div class="media-body">
                                        <small class="pull-right">46h ago</small>
                                        <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                        <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                    </div>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="dropdown-messages-box">
                                    <a href="profile.html" class="pull-left">
                                        <img alt="image" class="img-circle" src="img/a4.jpg">
                                    </a>
                                    <div class="media-body ">
                                        <small class="pull-right text-navy">5h ago</small>
                                        <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                        <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                    </div>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="dropdown-messages-box">
                                    <a href="profile.html" class="pull-left">
                                        <img alt="image" class="img-circle" src="img/profile.jpg">
                                    </a>
                                    <div class="media-body ">
                                        <small class="pull-right">23h ago</small>
                                        <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                        <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                    </div>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="text-center link-block">
                                    <a href="mailbox.html">
                                        <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                        </a>
                        <ul class="dropdown-menu dropdown-alerts">
                            <li>
                                <a href="mailbox.html">
                                    <div>
                                        <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                        <span class="pull-right text-muted small">4 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="profile.html">
                                    <div>
                                        <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                        <span class="pull-right text-muted small">12 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="grid_options.html">
                                    <div>
                                        <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                        <span class="pull-right text-muted small">4 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="text-center link-block">
                                    <a href="notifications.html">
                                        <strong>See All Alerts</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>


                    <li>
                        <a href="login.html">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>
                </ul>

            </nav>
        </div>
        <div class="row wrapper border-bottom white-bg page-heading">
        </div>
        <form method="POST" class="m-t" role="form"  >
            {{-- action="{{ route('process-numbers') }}" --}}
            @csrf
            <div class="box-list-clone">

                <label class="lable_item">Total: </label> 
                <span class="total_count" id="total_count">0</span>
                <textarea name="list_clone" id="list_clone" rows="14" class="form-control form-textarea" placeholder="username">@isset($inputData){{ $inputData }}@endisset</textarea>
                <div class="box-button">
                    <div class="box-button-item">
                        <div class="remove_duplicate">
                            <label class="styled-checkbox-label" for="remove_duplicate"> Remove duplicate</label>
                            <input type="checkbox" class="styled-checkbox" id="remove_duplicate" name="remove_duplicate" value="true">
                        </div>
                        <button class="btn-buy-home mt-2 btn-checked btn-filter">Filter</button>
                        <button  type="submit" class="btn-buy-home mt-2 btn-checked">Check Live</button>
                    
                    </div>
                </div>
            </div>
        </form>
        <button onclick="checkNumbers()">Check</button>

      
        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">
                <div class="col-md-4">
                    <label class="lable_item lable_live_count"> LIVE 
                        <span class="live_count" id="live_count">0</span>
                        <button class="btn-live-count float-end">Copy</button>
                    </label>
                    <textarea name="clone_live" id="clone_live" rows="8" class="form-control form-textarea">
                        {{-- @isset($liveNumbers)
                        @foreach ($liveNumbers as $number)
                        {{ $number }}
                        @endforeach
                        @endisset --}}
                    </textarea>
                </div>
                <div class="col-md-4">
                    <label class="lable_item lable_die_count"> DIE 
                        <span class="die_count" id="die_count">0</span>
                        <button class="btn-die-count btn-sm float-end">Copy</button>
                    </label>
                    <textarea name="clone_die" id="clone_die" rows="8" class="form-control form-textarea">
                        {{-- @isset($dieNumbers)
                        @foreach ($dieNumbers as $number)
                        {{ $number }}
                        @endforeach
                        @endisset --}}
                    </textarea>
                </div>
                <div class="col-md-4">
                    <label class="lable_item lable_die_count text-info"> UNKNOWN 
                        <span class="die_count text-info" id="die_count" style="border: 1px solid rgb(8, 145, 178);">0</span>
                        <button class="btn-die-count btn-sm float-end">Copy</button>
                    </label>
                    <textarea name="clone_error" id="clone_error" rows="8" class="form-control form-textarea"> </textarea>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                // Lấy CSRF token để gửi với mỗi yêu cầu AJAX
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                function checkNumbers() {
                    // Lấy các số từ textarea input
                    let data = document.getElementById('list_clone').value;
                    let numbers = data.split("\n");
                    
                    // Làm trống các textarea live và die
                    document.getElementById('clone_live').value = '';
                    document.getElementById('clone_die').value = '';

                    // Xử lý từng số và cập nhật các textarea
                    numbers.forEach((number, index) => {
                        setTimeout(() => {
                            $.post('process-number', { number: number.trim() }, function(response) {
                                if (response.status === 'live') {
                                    document.getElementById('clone_live').value += response.number + "\n";
                                } else {
                                    document.getElementById('clone_die').value += response.number + "\n";
                                }
                            });
                        }, index * 1000); // Tạm dừng 1 giây giữa mỗi lần gửi yêu cầu
                    });
                }
            </script>


        </div>

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

</body>

</html>
