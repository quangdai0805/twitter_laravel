$(document).ready(function() {
    $('#checkButton').click(function() {
        let data = document.getElementById('usernames').value;
        let usernames = data.split("\n");


        let live_count = 0;
        let lock_count = 0;
        let suppend_count = 0;

        document.getElementById('total_count').innerText = usernames.length;

        // Làm trống các textarea live và die
        document.getElementById('clone_live').value = '';
        document.getElementById('clone_lock').value = '';
        document.getElementById('clone_suppend').value = '';
        

        console.log(usernames);

        
        for (const username of usernames) {
            console.log('Sending username: ' + username);

            $.ajax({
                url: '/check_x?username='+username,
                type: 'GET',
                
                success: function(response) {
                    console.log('Received response:', response);

                    if (response.profile_interstitial_type === '') {

                        live_count++;
                        document.getElementById('clone_live').value += username + "\n";
                        document.getElementById('live_count').innerText = live_count;

                    } else if (response.profile_interstitial_type === 'fake_account') {
                        lock_count++;

                        document.getElementById('clone_lock').value += username + "\n";
                        document.getElementById('lock_count').innerText = lock_count;
                    } else {

                        suppend_count++;

                        document.getElementById('clone_suppend').value += username + "\n";
                        document.getElementById('suppend_count').innerText = suppend_count;
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error fetching guest token:', error);
                    reject(error);
                }
            });
        }
    });
});
