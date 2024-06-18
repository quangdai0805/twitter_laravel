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
</head>
<body>
    <h1>Live Numbers</h1>
    <textarea id="liveNumbers"></textarea>

    <h1>Die Numbers</h1>
    <textarea id="dieNumbers"></textarea>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Lấy CSRF token để gửi với mỗi yêu cầu AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Lấy danh sách số từ server hoặc nhập tay
        let numbers = {!! json_encode(explode("\n", $request->input('list_clone'))) !!};

        numbers.forEach(number => {
            $.post('/process-number', {number: number}, function(response) {
                if (response.status === 'live') {
                    $('#liveNumbers').append(response.number + "\n");
                } else {
                    $('#dieNumbers').append(response.number + "\n");
                }
            });
        });
    </script>
</body>
</html>
