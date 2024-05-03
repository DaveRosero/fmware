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
});
