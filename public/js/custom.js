let guestToken = '';
let requestCount = 0;
const requestsPerTokenChange = 50; // Số lượng request trước khi đổi token
const parallelRequests = 3; // Số lượng request song song để tăng tốc độ

document.addEventListener('DOMContentLoaded', (event) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log('CSRF Token:', csrfToken);

    document.getElementById('checkButton').addEventListener('click', async () => {
        await startCheck(csrfToken);
    });
});

async function fetchNewGuestToken() {
    try {
        const response = await fetch('/x_guest_token', {
            method: 'GET',
        });
        const data = await response.json();
        guestToken = data.guest_token;
        console.log('Received guest token:', guestToken);
    } catch (error) {
        console.log('Error fetching guest token:', error);
    }
}

async function sendRequestWithGuestToken(csrfToken, username) {
    try {
        const response = await fetch('/testt', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ username: username.trim(), guestToken: guestToken }),
        });
        const data = await response.json();
        console.log('Received response:', data);

        if (data.profile_interstitial_type === '') {
            document.getElementById('clone_live').value += username + "\n";
        } else if (data.profile_interstitial_type === 'fake_account') {
            document.getElementById('clone_lock').value += username + "\n";
        } else {
            document.getElementById('clone_suppend').value += username + "\n";
        }
    } catch (error) {
        console.log('Error:', error);
    }
}

async function startCheck(csrfToken) {
    await fetchNewGuestToken();

    let data = document.getElementById('usernames').value;
    let usernames = data.split("\n");

    // Làm trống các textarea live và die
    document.getElementById('clone_live').value = '';
    document.getElementById('clone_lock').value = '';
    document.getElementById('clone_suppend').value = '';

    let index = 0;

    async function handleNext() {
        if (index < usernames.length) {
            await sendRequestWithGuestToken(csrfToken, usernames[index]);
            requestCount++;
            index++;

            if (requestCount >= requestsPerTokenChange) {
                await fetchNewGuestToken();
                requestCount = 0;
            }

            handleNext();
        }
    }

    // Khởi tạo một nhóm các yêu cầu ban đầu
    for (let i = 0; i < parallelRequests && i < usernames.length; i++) {
        handleNext();
    }
}
