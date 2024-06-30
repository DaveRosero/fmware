$(document).ready(function(){
    $('#register').on('submit', function(event){
        event.preventDefault();
        $('#loadingOverlay').show();

        $.ajax({
            url: '/user-register',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                if (feedback.exist){
                    $('#register_feedback').text(feedback.exist);
                }

                if (feedback.password){
                    $('#register_feedback').text(feedback.password);
                }

                if (feedback.confirm) {
                    $('#register_feedback').text(feedback.confirm);
                }

                if (feedback.phone) {
                    $('#register_feedback').text(feedback.phone);
                }

                if (feedback.redirect) {
                    Swal.fire({
                        title: 'You have registered successfully!',
                        text: "A confirmation has been to your email.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'Go to login',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = feedback.redirect;
                        }
                    });
                }
            },
            complete: function(){
                $('#loadingOverlay').hide();
            }
        });
    });

    $('#show_password').change(function(){
        if($(this).is(':checked')) {
            $('#password').prop('type', 'text');
            $('#confirm').prop('type', 'text');  
        } else {
            $('#password').prop('type', 'password');
            $('#confirm').prop('type', 'password');  
        }
    });
    
    $('#brgy').change(function(){
        var brgy = $(this).val();
        console.log('changed');

        $.ajax({
            url: '/get-municipality',
            method: 'POST',
            data: {
                brgy : brgy
            },
            dataType: 'json',
            success: function(feedback){
                console.log('success');
                console.log(feedback.municipality);
                if (feedback.municipality) {
                    $('#municipality').val(feedback.municipality);
                }
            }
        });
    });
});
