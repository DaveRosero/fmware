$(document).ready(function(){
    function updateUserStatus (user_id, status) {
        $.ajax({
            url: '/update-user-status',
            method: 'POST',
            data: {
                user_id : user_id,
                status : status
            },
            dataType: 'json',
            success: function(json) {
                if (json.status == 1) {
                    var titleText = 'User Account is now enabled.'; 
                } else {
                    var titleText = 'User Account is now disabled.'; 
                }

                if (json.redirect) {
                    Swal.fire({
                        title: titleText,
                        icon: 'success',
                        confirmButtonText: 'Okay',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = json.redirect;
                        }
                    });

                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    }

    $('#user-table').DataTable();

    $('.status').on('click', function(){
        var user_id = $(this).data('user-id');
        var checkbox = $(this);

        if ($(this).is(':checked')) {
            Swal.fire({
                title: 'Enabling User Account',
                text: "Do you want to enable this user?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, enable it!',
                cancelButtonText: 'No, cancel!',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    var status = 1;
                    updateUserStatus(user_id, status);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    checkbox.prop('checked', false);
                }
            });
        } else {
            Swal.fire({
                title: 'Disabling User Account',
                text: "Do you want to disable this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, disable it!',
                cancelButtonText: 'No, cancel!',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    var status = 0;
                    updateUserStatus(user_id, status);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    checkbox.prop('checked', true);
                }
            });
        }
    });
});