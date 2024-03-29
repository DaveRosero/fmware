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
                $('#checkout-total').text(feedback.total);
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

    $('#checkout').on('click', function(){
        var id = $(this).data('user-id');
        $.ajax({
            url: '/fmware/has-address',
            method: 'POST',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(feedback){
                console.log(feedback);
                if (feedback.has_address) {
                    $('#checkout-form').modal('show');
                }

                if (feedback.no_address) {
                    $('#address-label').text(feedback.no_address);
                    $('#address-form').modal('show');
                }
            }
        });
    });
    
    $('#checkout-address').on('click', function(){
        $('#checkout-form').modal('hide');
        $('#address-form').modal('show');
    });

    $('#newAddress').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url: '/fmware/new-address',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                if (feedback.redirect) {
                    window.location.href = feedback.redirect
                }
            }
        });
    });

    $('#place-order').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url: '/fmware/checkout',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                console.log(feedback);
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
            }
        });
    });
})