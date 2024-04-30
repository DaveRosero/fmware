$(document).ready(function(){
    $('#search-form').submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '/search-product',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'html',
            success: function(feedback) {
                $('#products').html(feedback);
            }
        });
    });
});