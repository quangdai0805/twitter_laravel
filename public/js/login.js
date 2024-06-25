$(document).ready(function() {
    $('#loginAccount').click(function() {
        //alert('Please select at least one account.');
        // var selectedAccounts = [];
        // $('input[name="accounts[]"]:checked').each(function() {
        //     selectedAccounts.push($(this).val());
        // });

        var selectedAccounts = [];
        var checkboxes = document.querySelectorAll('input[name="selected_accounts[]"]:checked');

        for (var checkbox of checkboxes) {
            selectedAccounts.push(checkbox.value);
        }


        if (selectedAccounts.length === 0) {
            alert('Please select at least one account.');
            return;
        }else{
            alert(selectedAccounts.length);
        }

        $.ajax({
            url: '/LoginAccount',
            type: 'POST',
            data: {
                accounts: selectedAccounts[0],
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Request was successful!');
                console.log(response);
            },
            error: function(xhr, status, error) {
                alert('An error occurred.');
                console.log(error);
            }
        });
    });

    $('#followAccount').click(function() {
        var selectedAccounts = [];
        let data = document.getElementById('idAccountFollow').value;


        if (data === '') {
            alert('Dien UID');
            return;
        }else{
            //alert(selectedAccounts.length);
        }

        $.ajax({
            url: '/LikePost',
            type: 'POST',
            data: {
                postid: data,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                //alert('Request was successful!');
                console.log(response);
            },
            error: function(xhr, status, error) {
                //alert('An error occurred.');
                console.log(error);
            }
        });
    });
    //followAccount
});

