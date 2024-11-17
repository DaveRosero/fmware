$(document).ready(function () {
    function updateStaff(active, id) {
        console.log('staff update ajax');

        $.ajax({
            url: '/update-staff',
            method: 'POST',
            data: {
                active: active,
                id: id
            },
            dataType: 'text',
            success: function (feedback) {
                console.log(feedback);
                if (feedback === '/staff') {
                    window.location.href = feedback;
                }
            }
        });
    }

    $('#staff-table').DataTable();

    $('#show_password').change(function () {
        if ($(this).is(':checked')) {
            $('#password').prop('type', 'text');
            $('#confirm').prop('type', 'text');
        } else {
            $('#password').prop('type', 'password');
            $('#confirm').prop('type', 'password');
        }
    });

    $('#add-staff').submit(function (event) {
        event.preventDefault();
        $('#loadingOverlay').show();

        $.ajax({
            url: '/add-staff',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'text',
            success: function (feedback) {
                console.log(feedback);
                if (feedback === '/staff') {
                    window.location.href = feedback;
                } else {
                    $.notify(feedback, 'error');
                }

            },
            complete: function () {
                $('#loadingOverlay').hide(); // Hide the loading overlay after the request completes
            }
        });
    });

    $('#staff-table').on('change', '.status', function () {
        var id = $(this).data('user-id');
        var checkbox = $(this);

        if ($(this).is(':checked')) {
            Swal.fire({
                title: 'Enabling Staff Account',
                text: "Do you want to enable this staff account?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, enable it!',
                cancelButtonText: 'No, cancel!',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    var active = 1;
                    updateStaff(active, id);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    checkbox.prop('checked', false);
                }
            });
        } else {
            Swal.fire({
                title: 'Disabling Staff Account',
                text: "Do you want to disable this account?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, disable it!',
                cancelButtonText: 'No, cancel!',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    var active = 0;
                    updateStaff(active, id);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    checkbox.prop('checked', true);
                }
            });
        }
    });

    // Function to generate an alphanumeric random password
    function generatePassword(length = 8) {
        const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        let password = "";
        for (let i = 0; i < length; i++) {
            password += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        return password;
    }

    // Handle checkbox state change
    $("#generate_password_checkbox").on("change", function () {
        if ($(this).is(":checked")) {
            const password = generatePassword();
            $("#password, #confirm").val(password).prop("readonly", true); // Fill inputs and make them readonly
        } else {
            $("#password, #confirm").val("").prop("readonly", false); // Clear inputs and make them editable
        }
    });
});