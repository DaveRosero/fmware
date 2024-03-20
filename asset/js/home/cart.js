$(document).ready(function(){
    $(document).on('click', '.add-cart', function(){
        var parentButton = $(this).closest('.add-cart');
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

                if (feedback.cart_feedback) {
                    parentButton.notify(feedback.cart_feedback, {
                        position: 'right'
                    }, 'info');
                }
            }
        });
    });
})