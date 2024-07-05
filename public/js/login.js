$(document).ready(function() {
    $('#loginAccount').click(function() {
        var checkboxes = document.querySelectorAll('input[name="selected_accounts[]"]:checked');
        checkboxes.forEach(function(checkbox) {
            console.log('Sending username: ' + checkbox.value);
            $.ajax({
                url: '/LoginAccount',
                type: 'POST',
                data: {
                    accounts: checkbox.value,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    });

    $('#likePost').click(function() {

        let postid = document.getElementById('idAccountFollow').value;

        if (postid === '') {
            alert('Dien UID');
            return;
        }
        var checkboxes = document.querySelectorAll('input[name="selected_accounts[]"]:checked');
        checkboxes.forEach(function(checkbox) {
            console.log('Sending username: ' + checkbox.value);
            $.ajax({
                url: '/LikePost',
                type: 'POST',
                data: {
                    postid: postid,
                    accounts: checkbox.value,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    });
    $('#commentPost').click(function() {

        let postid = document.getElementById('idAccountFollow').value;

        if (postid === '') {
            alert('Dien UID');
            return;
        }
        var checkboxes = document.querySelectorAll('input[name="selected_accounts[]"]:checked');
        checkboxes.forEach(function(checkbox) {
            console.log('Sending username: ' + checkbox.value);
            $.ajax({
                url: '/comment-post',
                type: 'POST',
                data: {
                    postid: postid,
                    accounts: checkbox.value,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    });

    $('#checkip').click(function() {

        
        var checkboxes = document.querySelectorAll('input[name="selected_accounts[]"]:checked');
        checkboxes.forEach(function(checkbox) {
            console.log('Sending username: ' + checkbox.value);
            $.ajax({
                url: '/CheckProxy',
                type: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    });

});

