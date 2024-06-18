let guestToken = '';
let requestCount = 0;
const requestsPerTokenChange = 3;

$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token:', csrfToken);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    $('#checkButton').on('click', function() {
        startCheck();
    });
});

function fetchNewGuestToken() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/x_guest_token',
            type: 'GET',
            success: function(response) {
                guestToken = response.guest_token;
                console.log('Received guest token:', guestToken);
                resolve();
            },
            error: function(xhr, status, error) {
                console.log('Error fetching guest token:', error);
                reject(error);
            }
        });
    });
}

function sendRequestWithGuestToken(username) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/testt',
            type: 'POST',
            data: { username: username.trim(), guestToken: guestToken },
            success: function(response) {
                console.log('Received response:', response);

                if (response.profile_interstitial_type === '') {
                    document.getElementById('clone_live').value += username + "\n";
                } else if (response.profile_interstitial_type === 'fake_account') {
                    document.getElementById('clone_lock').value += username + "\n";
                } else {
                    document.getElementById('clone_suppend').value += username + "\n";
                }
                resolve();
            },
            error: function(xhr, status, error) {
                console.log('Error:', error);
                reject(error);
            }
        });
    });
}

async function startCheck() {
    await fetchNewGuestToken();

    let data = document.getElementById('usernames').value;
    let usernames = data.split("\n");

    // Làm trống các textarea live và die
    document.getElementById('clone_live').value = '';
    document.getElementById('clone_lock').value = '';
    document.getElementById('clone_suppend').value = '';

    // Xử lý từng username và gửi yêu cầu AJAX với guestToken hiện tại
    for (const username of usernames) {
        console.log('Sending username:', username.trim());
        await sendRequestWithGuestToken(username.trim());

        // Tăng biến đếm số lần yêu cầu đã được gửi
        requestCount++;

        // Nếu đã gửi đủ số lần yêu cầu cần thiết, thì lấy guestToken mới
        if (requestCount % requestsPerTokenChange === 0) {
            await fetchNewGuestToken();
        }
    }
}
