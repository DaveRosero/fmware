$(document).ready(function(){
    $('.add-cart').on('click', function(){
        $.ajax({
            url: '/fmware/add-cart',
            method: 'POST',
            data: {
                id: $(this).data('product-id')
            },
            dataType: 'json',
            success: function(feedback) {
                if (feedback.cart_count) {
                    $('#cart-count').text(feedback.cart_count);
                }
            }
        });
    });
})