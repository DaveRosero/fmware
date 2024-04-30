$(document).ready(function(){
    $('#new-staff').submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '/new-staff',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'text',
            success: function(feedback) {
                console.log(feedback);
                if (feedback) {
                    window.location.href = feedback;
                }
            }
        });
    });
});