<!DOCTYPE html>
<html>
<head>
    <title>Main View</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        textarea {
            width: 300px;
            height: 150px;
        }
    </style>
     <link href="css/bootstrap.min.css" rel="stylesheet">
     <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
 
     <!-- Toastr style -->
     <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">
 
     <link href="css/animate.css" rel="stylesheet">
     <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="body-content-user wrapper wrapper-content animated fadeInRight">
        <table class="table table-hover margin bottom">
            <thead>
            <tr>
                <th style="width: 1%" class="text-center">ID</th>
                <th>User Name</th>
                <th class="text-center">Status</th>
                <th class="text-center">Following</th>
                <th class="text-center">Follower</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">1</td>
                <td> Security doors</td>
                <td class="text-center small">16 Jun 2014</td>
                <td class="text-center"><span class="label label-primary">$483.00</span></td>
                <td class="text-center"><span class="label label-primary">$483.00</span></td>
            </tr>
            </tbody>
        </table>
    </div>
    
    <h1>Input Numbers</h1>
    <div class="body-content-user wrapper wrapper-content animated fadeInRight">
        <div class="box-list-clone">
            <label class="lable_item lable_live_count"> Total 
                <span class="live_count" id="live_count">0</span>
                <button class="btn-live-count float-end">Copy</button>
            </label>
            <textarea name="usernames" id="usernames" rows="14" class="form-control form-textarea"></textarea>
        </div>

        <button id="checkButton" class="btn-buy-home mt-2 btn-checked">Check</button>

        <div class="row">
            <div class="col-md-4">
                <label class="lable_item lable_live_count"> LIVE 
                    <span class="live_count" id="live_count">0</span>
                    <button class="btn-live-count float-end">Copy</button>
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
                    <span class="die_count text-info" id="die_count" style="border: 1px solid rgb(8, 145, 178);">0</span>
                    <button class="btn-die-count btn-sm float-end">Copy</button>
                </label>
                <textarea name="clone_error" id="clone_suppend" rows="8" class="form-control form-textarea"></textarea>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/custom.js"></script>
    
</body>
</html>
