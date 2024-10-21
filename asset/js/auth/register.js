$(document).ready(function () {
    $('#register').on('submit', function (event) {
        event.preventDefault();
        $('#loadingOverlay').show();

        // Clear previous feedback messages and validation errors
        $('.invalid-feedback').remove();
        $('.form-control').removeClass('is-invalid');
        $('#register_feedback').empty();

        $.ajax({
            url: '/user-register', // The backend route that handles registration
            method: 'POST',
            data: $(this).serialize(), // Serialize form data
            dataType: 'json',
            success: function (feedback) {
                // Check if there are any validation errors
                if (feedback.exist) {
                    $('#email').addClass('is-invalid').after('<div class="invalid-feedback">' + feedback.exist + '</div>');
                }
                if (feedback.password_length) {
                    $('#password').addClass('is-invalid').after('<div class="invalid-feedback">' + feedback.password_length + '</div>');
                }
                if (feedback.password_alphanumeric) {
                    $('#password').addClass('is-invalid').after('<div class="invalid-feedback">' + feedback.password_alphanumeric + '</div>');
                }
                if (feedback.confirm) {
                    $('#confirm').addClass('is-invalid').after('<div class="invalid-feedback">' + feedback.confirm + '</div>');
                }
                if (feedback.phone) {
                    $('#phone').addClass('is-invalid').after('<div class="invalid-feedback">' + feedback.phone + '</div>');
                }

                // If registration is successful, handle redirection
                if (feedback.redirect) {
                    Swal.fire({
                        title: 'You have registered successfully!',
                        text: "A confirmation email has been sent to your email.",
                        icon: 'success',
                        confirmButtonText: 'Go to login',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = feedback.redirect;
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                // Handle any unexpected errors during the request
                console.error('Error occurred: ' + error);
                $('#register_feedback').text('An unexpected error occurred. Please try again later.');
            },
            complete: function () {
                $('#loadingOverlay').hide(); // Hide the loading overlay after the request completes
            }
        });
    });

    $('#show_password').change(function () {
        if ($(this).is(':checked')) {
            $('#password').prop('type', 'text');
            $('#confirm').prop('type', 'text');
        } else {
            $('#password').prop('type', 'password');
            $('#confirm').prop('type', 'password');
        }
    });

    $('#brgy').change(function () {
        var brgy = $(this).val();
        console.log('changed');

        $.ajax({
            url: '/get-municipality',
            method: 'POST',
            data: {
                brgy: brgy
            },
            dataType: 'json',
            success: function (feedback) {
                console.log('success');
                console.log(feedback.municipality);
                if (feedback.municipality) {
                    $('#municipality').val(feedback.municipality);
                }
            }
        });
    });
});
