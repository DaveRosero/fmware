$(document).ready(function(){
    $('#search-form').submit(function(event){
        event.preventDefault();
        console.log('form submitted');

        $.ajax({
            url: '/search-product',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'html',
            success: function(feedback) {
                console.log(feedback);
                $('#products').html(feedback);
            }
        });
    });
});