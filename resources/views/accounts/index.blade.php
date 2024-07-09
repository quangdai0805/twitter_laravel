{{-- @include('mainTemp') --}}

@extends('layout')
@section('title')
    Accounts
@endsection
@section('content')
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
                    
                    <div class="ibox-content">
                        
                        <div class="table-responsive">
                            <form action="{{ route('accounts.index') }}" method="GET">
                                <label for="per_page">Số lượng bản ghi mỗi trang:</label>
                                <select name="per_page" id="per_page">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <!-- Thêm các tùy chọn khác tại đây nếu cần -->
                                </select>
                                <button type="submit">Áp dụng</button>
                            </form>

                            <form method="POST" action="{{ route('deleteSelected') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mb-3">Delete Selected</button>
                                <button type="button" id="show-cookies" class="btn btn-primary mb-3">Show Cookies</button>
                            
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"></th>
                                            <th>#</th>
                                            <th>User Name</th>
                                            <th>Proxy</th>
                                            <th>Follower</th>
                                            <th>Following</th>
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
                                            <td>
                                            
                                                    @if ($account->cookies)
                                                        {{-- <span style="color: green;">{{ $account->cookies }}</span> --}}
                                                        <span class="cookies-status"  style="color: green;" value="{{ $account->cookies }}">Logged In</span>
                                                    @else
                                                        <span style="color: red;">-</span>
                                                    @endif

                                            </td>
                                        </tr>
                                        @endforeach
                                        {{ $accounts->links() }}
                                    </tbody>
                                </table>
                            </form>
                            {{-- <form method="POST" action="{{ route('deleteSelected') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mb-3">Delete Selected</button>
                                <button  >Show Cookies</button>

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
                                                <td>
                                                    @if ($account->cookies)
                                                        <span style="color: green;">Logged In</span>
                                                    @else
                                                        <span style="color: red;">Not Logged In</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            
                                        @endforeach
                                        {{ $accounts->links() }}
                                    </tbody>

                                </table>
                            </form> --}}

                           
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
                                        <button id= "loginAccount" type="button" class="btn btn-sm btn-primary"> Login</button> 
                                    </li>
                                    <li>
                                        <div class="input-group col-sm-12">
                                            <span class="input-group-btn">
                                                <button id= "likePost" type="button" class="btn btn-sm btn-primary"> Like Post </button> 
                                                <button id= "tweetPost" type="button" class="btn btn-sm btn-primary"> Tweet </button> 
                                                <button id= "commentPost" type="button" class="btn btn-sm btn-primary"> Comment </button> 
                                              
                                            </span>

                                            <span>
                                                <input id='idAccountFollow' type="text" placeholder="UID" class="input-sm form-control">
                                            </span>
                                            
                                        </div>
                                        <div class="input-group col-sm-12">
                                            
                                            <span>
                                                <textarea id="comments" placeholder="Comments" rows="3" class="form-control form-textarea"></textarea>
                                            </span>
                                            
                                        </div>

                                    </li>
                                    <li>
                                        <div class="input-group col-sm-6">
                                            <span class="input-group-btn">
                                                <button  type="button" class="btn btn-sm btn-primary"> Follow</button> 
                                                {{-- <button id='checkip' type="button" class="btn btn-sm btn-primary"> Check IP</button>  --}}
                                            </span>

                                            <input type="text" placeholder="UID" class="input-sm form-control">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div> 

                    </div>
                </div>

                <form method="POST" class="m-t" role="form" action="{{ route('accounts.store') }}" >
                    @csrf
                    <div class="box-list-clone">
                        <label class="lable_item lable_live_count"> Total 
                            <span class="live_count" id="live_count">0</span>
                            <button type="submit" class="btn btn-sm btn-primary">Add</button>
                            <!-- @if (session('error'))
                                <div class="alert alert-danger">
                                    {{-- {{ session('error') }} --}}
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif -->

                        </label>
                        <textarea name="accounts" id="accounts" rows="14" class="form-control form-textarea"></textarea>
                    </div>
                </form>

                
            </div>

        </div>

    </div>

</div>
@endsection 
@section('js')
<script>
    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.querySelectorAll('input[name="selected_accounts[]"]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
            // console.log(checkbox);
        }
    }
</script>
<script>
    document.getElementById('show-cookies').onclick = function() {
        // console.log(this.innerText);
        if(this.innerText == "Show Cookies"){
            // console.log('bgio tao show cookies ne');
            this.innerText = "Hide Cookies";
            // Select all checkboxes with class 'cookies-status'
            const checkboxes = document.querySelectorAll('span.cookies-status');

            // Loop through each checkbox
            checkboxes.forEach(function(checkbox) {
                // Get the value of the checkbox

                var valueAttribute = checkbox.getAttribute('value');
                // Hiển thị giá trị lấy được trong console
                // console.log(valueAttribute); // In ra giá trị của thuộc tính "value"
                // var checkboxValue = checkbox.innerText;
                // console.log(checkbox.value);
                // Update the innerText of the checkbox
                checkbox.innerText = valueAttribute;
            });
        
        }else{
            // console.log('bgio tao k show nua');
            this.innerText = "Show Cookies";
            const checkboxes = document.querySelectorAll('span.cookies-status');

            // Loop through each checkbox
            checkboxes.forEach(function(checkbox) {
                // Get the value of the checkbox

                // var valueAttribute = checkbox.getAttribute('value');
                // Hiển thị giá trị lấy được trong console
                // console.log(valueAttribute); // In ra giá trị của thuộc tính "value"
                // var checkboxValue = checkbox.innerText;
                // console.log(checkbox.value);
                // Update the innerText of the checkbox
                checkbox.innerText = 'Logged In';
            });
        }
        
    }
</script>
{{-- <script>
    document.getElementById('show-cookies').onclick = function() {
        
        // var checkboxes = document.querySelectorAll('span[class="cookies-status"]');

        const checkboxes = document.querySelectorAll('span[class="cookies-status"]');


        for (var checkbox of checkboxes) {
            console.log(checkbox);
        }
    }
</script> --}}

<script src="js/login.js"></script>
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
@endsection