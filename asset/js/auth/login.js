$(document).ready(function(){
    $('#login').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/user-login',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                console.log(feedback);
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
                
                $('#login_feedback').text(feedback.login_feedback);
            }
        });
    });

    $('#forgot-form').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/forgot-password',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json) {
                console.log(json);
                if (json.forgot_feedback) {
                    $('#forgot-feedback').text(json.forgot_feedback);
                }
                
                if (json.forgot_success) {
                    $('#forgot-feedback').text('');
                    $('#forgot-success').text(json.forgot_success);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ', status, error);
                console.log('Response Text: ', xhr.responseText);
            }
        });
    });

    $('#show_password').change(function(){
        if($(this).is(':checked')) {
            $('#password').prop('type', 'text');
        } else {
            $('#password').prop('type', 'password');
        }
    });

    $('#forgot-password').on('click', function(){
        $('#forgot-modal').modal('show');
    });

    $('#reset-form').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/new-password',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json) {
                if (json.redirect) {
                    window.location.href = json.redirect;
                }
            }
        });
    });

    $('#show_new').change(function(){
        if($(this).is(':checked')) {
            $('#password').prop('type', 'text');
            $('#confirm').prop('type', 'text');
        } else {
            $('#password').prop('type', 'password');
            $('#confirm').prop('type', 'password');
        }
    });
});