$(document).ready(function(){
    $('#register').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/user-register',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                if(feedback.verify){
                    $.notify(feedback.verify, 'success');
                }
            }
        });
    });
});