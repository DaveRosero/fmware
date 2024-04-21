$(document).ready(function(){
    $('#login').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/user-login',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
                $('#login_feedback').text(feedback.login_feedback);
            }
        });
    });
});