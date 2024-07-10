$(document).ready(function() {
    $('#checkButton').click(function() {
        let data = document.getElementById('usernames').value;
        let usernames = data.split("\n");

        // Xác định số lượng request cần gửi
        let chunkSize = 50;
        let totalChunks = Math.ceil(usernames.length / chunkSize);

        // Lặp qua từng phần của usernames và gửi request POST
        let currentIndex = 0;
        let live_count = 0;
        let lock_count = 0;
        let suppend_count = 0;

        document.getElementById('total_count').innerText = usernames.length;
        document.getElementById('clone_live').value = '';
        document.getElementById('clone_lock').value = '';
        document.getElementById('clone_suppend').value = '';

        function sendRequest(chunk) {
            $.ajax({
                url: '/testttt',
                type: 'POST',
                dataType: 'json',
                data: {
                    accounts: chunk,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    response.result.forEach(element => {
                        if (element.status === 'Live') {
                            live_count++;
                            document.getElementById('clone_live').value += element.email + "\n";
                            document.getElementById('live_count').innerText = live_count;
                        } else if (element.status === 'Lock') {
                            lock_count++;
                            document.getElementById('clone_lock').value += element.email + "\n";
                            document.getElementById('lock_count').innerText = lock_count;
                        } else {
                            suppend_count++;
                            document.getElementById('clone_suppend').value += element.email + "\n";
                            document.getElementById('suppend_count').innerText = suppend_count;
                        }
                    });

                    currentIndex++;
                    if (currentIndex < totalChunks) {
                        sendRequest(usernames.slice(currentIndex * chunkSize, (currentIndex + 1) * chunkSize));
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        // Bắt đầu gửi yêu cầu với các phần đầu tiên
        sendRequest(usernames.slice(0, chunkSize));
    });
});

// $(document).ready(function() {
//     $('#checkButton').click(function() {
//         let data = document.getElementById('usernames').value;
//         let usernames = data.split("\n");


//         let live_count = 0;
//         let lock_count = 0;
//         let suppend_count = 0;

//         document.getElementById('total_count').innerText = usernames.length;

//         // Làm trống các textarea live và die
//         document.getElementById('clone_live').value = '';
//         document.getElementById('clone_lock').value = '';
//         document.getElementById('clone_suppend').value = '';
    
//         console.log(usernames);
//         $.ajax({
//             url: '/testttt',
//             type: 'POST',
//             datatype: 'json',
//             data: {
//                 accounts: usernames,
//                 _token: $('meta[name="csrf-token"]').attr('content')
//             },
//             success: function(response) {
//                 // console.log(response);
//                 // console.log(response.result);
//                 response.result.forEach(element => {
//                     // console.log(element)
//                     if (element.status === 'Live') {

//                         live_count++;
//                         document.getElementById('clone_live').value += element.email + "\n";
//                         document.getElementById('live_count').innerText = live_count;
    
//                     } else if (element.status === 'Lock') {
//                         lock_count++;
    
//                         document.getElementById('clone_lock').value += element.email + "\n";
//                         document.getElementById('lock_count').innerText = lock_count;
//                     } else {
    
//                         suppend_count++;
//                         document.getElementById('clone_suppend').value += element.email + "\n";
//                         document.getElementById('suppend_count').innerText = suppend_count;
//                     }

//                 });
               

                
                
//             },
//             error: function(xhr, status, error) {
//                 console.log(error);
//             }
//         });
//     });
// });
