$(document).ready(function(){
    function getCartTotal () {
        var id = $('#cart-total').data('user-id');
        $.ajax({
            url: '/fmware/cart-total',
            method: 'POST',
            data: {
                id : id
            },
            success: function(feedback){
                $('#cart-total').text(feedback);
            }
        });
    }

    getCartTotal();
      
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
                        position: 'right',
                        className: 'info'
                    });
                }
            }
        });
    });

    $(document).on('click', '.addQty', function(){
        var qty = $(this).siblings('.qty');
        var subtotal = $(this).closest('tr').find('.subtotal');
        $.ajax({
            url: '/fmware/add-qty',
            method: 'POST',
            data: {
                id: $(this).data('cart-id')
            },
            dataType: 'json',
            success: function(feedback){
                qty.val(feedback.qty);
                subtotal.text(feedback.subtotal);
                getCartTotal();
            }
        });
    });

    $(document).on('click', '.subQty', function(){
        var qty = $(this).siblings('.qty');
        var subtotal = $(this).closest('tr').find('.subtotal');

        if (qty.val() == 1) {
            return;
        }

        $.ajax({
            url: '/fmware/sub-qty',
            method: 'POST',
            data: {
                id: $(this).data('cart-id')
            },
            dataType: 'json',
            success: function(feedback){
                qty.val(feedback.qty);
                subtotal.text(feedback.subtotal);
                getCartTotal();
            }
        });
    });

    $('#cart-reset').on('click', function(){
        $('#reset-warning').modal('show');
    });

    $('#confirm-reset').on('click', function(){
        var id = $(this).data('user-id');
        $.ajax({
            url: '/fmware/reset-cart',
            method: 'POST',
            data: {
                id: id
            },
            dataType: 'text',
            success: function(feedback){
                if (feedback) {
                    window.location.href = feedback;
                }
            }
        });
    })
})