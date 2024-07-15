@extends('layout')
@section('title')
    X+ 
@endsection
@section('content')
<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="box-list-clone">
                    <label class="lable_item lable_live_count"> Total 
                        <span class="live_count" id="total_count">0</span>
                        <button class="btn-live-count float-end">Copy</button>
                    </label>
                    <textarea name="usernames" placeholder="username" id="usernames" rows="14" class="form-control form-textarea"></textarea>
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
@endsection 
@section('js')
    <script src="js/check_x_2.js"></script>
@endsection