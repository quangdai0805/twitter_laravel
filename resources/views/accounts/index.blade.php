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
                                                <td>
                                                    @if ($account->cookies)
                                                        Logined
                                                    @else
                                                        
                                                    @endif
                                                </td>
                                            </tr>
                                            
                                        @endforeach
                                        {{ $accounts->links() }}
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
        }
    }
</script>
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