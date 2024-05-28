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

    $('#show_password').change(function(){
        if($(this).is(':checked')) {
            $('#password').prop('type', 'text');
        } else {
            $('#password').prop('type', 'password');
        }
    });
});