$(document).ready(function(){
    $('.add-to-cart-btn').on('click', function(){
        $('#product-modal').modal('show');
    });

    $('#add-to-cart-form').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/fmware/add-cart',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback) {
                if (feedback.product_exist) {
                    $.notify(feedback.product_exist, 'error');
                }

                if (feedback.cart_count) {
                    $('#cart-count').text(feedback.cart_count);
                }

                if (feedback.product_added) {
                    $.notify(feedback.product_added, 'success');
                }
            }, error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
})