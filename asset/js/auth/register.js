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
                if(feedback.verify){
                    $.notify(feedback.verify, 'success');
                }

                if (feedback.exist){
                    $.notify(feedback.exist, 'error');
                }

                if (feedback.password){
                    $.notify(feedback.password, 'error');
                }

                if (feedback.confirm) {
                    $.notify(feedback.confirm, 'error');
                }

                if (feedback.phone) {
                    $.notify(feedback.phone, 'error');
                }

                if (feedback.redirect){
                    $('#overlay').show();
                    setTimeout(function() {
                        window.location.href = feedback.redirect;
                        $('#overlay').hide();
                    }, 3000);
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
