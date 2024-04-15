$(document).ready(function(){
    $('#confirm-order-form').submit(function(event){
        event.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: '/fmware/confirm-order-status',
            method: 'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function(){
            }
        });
    });
});