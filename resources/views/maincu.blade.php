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
    <h1>Input Accounts</h1>
    <textarea id="username"></textarea><br>
    <button onclick="checkNumbers()">Check</button>

    <div class="wrapper wrapper-content animated fadeInRight">
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
                <textarea name="clone_die" id="clone_die" rows="8" class="form-control form-textarea"></textarea>
            </div>
            <div class="col-md-4">
                <label class="lable_item lable_die_count text-info"> SUPPEND 
                    <span class="die_count text-info" id="die_count" style="border: 1px solid rgb(8, 145, 178);">0</span>
                    <button class="btn-die-count btn-sm float-end">Copy</button>
                </label>
                <textarea name="clone_error" id="clone_error" rows="8" class="form-control form-textarea"></textarea>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Lấy CSRF token để gửi với mỗi yêu cầu AJAX
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log(csrfToken)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        function checkNumbers() {
            $.ajax({
                        url: '/testt', // Thay thế bằng route của bạn
                        type: 'GET',
                        data: { username: username.trim() },
                        success: function(response) {
                            if (response.profile_interstitial_type === '') {
                                document.getElementById('clone_live').value += username + "\n";
                            } else {
                                document.getElementById('clone_die').value += username + "\n";
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error:', error);
                        }
                    });

            // Lấy các số từ textarea input
            let data = document.getElementById('username').value;
            let usernames = data.split("\n");
            
            // Làm trống các textarea live và die
            document.getElementById('clone_live').value = '';
            document.getElementById('clone_die').value = '';

            // Xử lý từng số và cập nhật các textarea
            usernames.forEach((username, index) => {
                setTimeout(() => {
                    $.ajax({
                        url: '/testt', // Thay thế bằng route của bạn
                        type: 'POST',
                        data: { username: username.trim() },
                        success: function(response) {
                            if (response.profile_interstitial_type === '') {
                                document.getElementById('clone_live').value += username + "\n";
                            } else {
                                document.getElementById('clone_die').value += username + "\n";
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error:', error);
                        }
                    });
                }, 1000 * index); // Tạm dừng 1 giây giữa mỗi lần gửi yêu cầu
            });
        }
    </script>

    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
</body>
</html>
