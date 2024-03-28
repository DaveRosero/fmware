$(document).ready(function(){
    function getCartTotal () {
        var id = $('#total').data('user-id');
        $.ajax({
            url: '/fmware/cart-total',
            method: 'POST',
            data: {
                id : id
            },
            dataType: 'json',
            success: function(feedback){
                $('#product-total').text(feedback.product_total);
                $('#total').text(feedback.total);
                $('#tax').text(feedback.tax);
            }
        });
    }

    function getDeliveryFee () {
        $.ajax({
            url: '/fmware/delivery-fee',
            method: 'POST',
            dataType: 'json',
            success: function(feedback){
                $('#delivery-fee').text(feedback.fee);
            }
        })
    }

    function getVAT () {
        $.ajax({
            url: '/fmware/vat',
            method: 'POST',
            dataType: 'json',
            success: function(feedback){
                $('#vat').text(feedback.vat);
            }
        })
    }

    getDeliveryFee();
    getVAT();
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
        var id = $(this).data('product-id');
        var qty = $('.qty_' + id);
        var subtotal = $('.subtotal_' + id);
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
        var id = $(this).data('product-id');
        var qty = $('.qty_' + id);
        var subtotal = $('.subtotal_' + id);

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

    $('#cart-checkout').on('click', function(){
        $('#checkout-form').modal('show');
    });
})