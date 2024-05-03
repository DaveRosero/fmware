$(document).ready(function(){
    function getCartTotal () {
        var id = $('#checkout').data('user-id');
        var delivery_fee = $('#delivery-fee-value').val();
        $.ajax({
            url: '/cart-total',
            method: 'POST',
            data: {
                id : id,
                delivery_fee, delivery_fee
            },
            dataType: 'json',
            success: function(feedback){
                console.log(feedback);
                if (feedback.product_total) {
                    $('#product-total').text(feedback.product_total);
                }

                if (feedback.checkout_total) {
                    $('#checkout-total').text(feedback.checkout_total);
                }
            }
        });
    }

    getCartTotal();

    // function getDeliveryFee () {
    //     $.ajax({
    //         url: '/delivery-fee',
    //         method: 'POST',
    //         dataType: 'json',
    //         success: function(feedback){
    //             $('#delivery-fee').text(feedback.fee);
    //         }
    //     })
    // }

    // function getVAT () {
    //     $.ajax({
    //         url: '/vat',
    //         method: 'POST',
    //         dataType: 'json',
    //         success: function(feedback){
    //             $('#vat').text(feedback.vat);
    //         }
    //     })
    // }

    // getDeliveryFee();
    // getVAT();
    // getCartTotal();
      
    // $(document).on('click', '.add-cart', function(){
    //     var parentButton = $(this).closest('.add-cart');
    //     $.ajax({
    //         url: '/add-cart',
    //         method: 'POST',
    //         data: {
    //             id: $(this).data('product-id')
    //         },
    //         dataType: 'json',
    //         success: function(feedback) {
    //             if (feedback.cart_count) {
    //                 $('#cart-count').text(feedback.cart_count);
    //             }

    //             if (feedback.cart_feedback) {
    //                 parentButton.notify(feedback.cart_feedback, {
    //                     position: 'right',
    //                     className: 'info'
    //                 });
    //             }
    //         }
    //     });
    // });

    $('#brgy').select2({
        dropdownParent: $('#address-form'),
        width: '100%',
        placeholder: 'Select your Baranggay'
    });

    $('#brgy').change(function(){
        var brgy = $(this).val();
        console.log('changed');

        $.ajax({
            url: '/get-municipality',
            method: 'POST',
            data: {
                brgy : brgy
            },
            dataType: 'json',
            success: function(feedback){
                console.log('success');
                console.log(feedback.municipality);
                if (feedback.municipality) {
                    $('#municipality').val(feedback.municipality);
                }
            }
        });
    });

    $('#address').change(function(){
        var brgy = $(this).val();
        var selected = $(this).find('option:selected');
        var address_id = selected.data('address-id');
        $.ajax({
            url: '/delivery-fee',
            method: 'POST',
            data: {
                brgy : brgy
            },
            dataType: 'json',
            success: function(feedback){
                if (feedback) {
                    $('#delivery-fee').text('₱' + feedback.delivery_fee + '.00');
                    $('#delivery-fee-value').val(feedback.delivery_value);
                    $('#address_id').val(address_id);
                    getCartTotal();
                }
            }
        });
    })
    
    $(document).on('click', '.addQty', function(){
        var id = $(this).data('product-id');
        var qty = $('.qty_' + id);
        var subtotal = $('.subtotal_' + id);
        $.ajax({
            url: '/add-qty',
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
            url: '/sub-qty',
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
            url: '/has-address',
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
            url: '/new-address',
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
            url: '/checkout',
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

    $('.cart-checkbox').click(function() {
        if ($(this).is(':checked')) {
            console.log('Checkbox clicked and checked', $(this).val(), $(this).data('user-id'));

            $.ajax({
                url: '/check-product',
                method: 'POST',
                data: {
                    user_id : $(this).data('user-id'),
                    product_id : $(this).val()
                },
                dataType: 'json',
                success: function(feedback) {
                    if (feedback.checked) {
                        getCartTotal();
                    }
                }
            })
        } else {
            console.log('Checkbox clicked and unchecked', $(this).val());

            $.ajax({
                url: '/uncheck-product',
                method: 'POST',
                data: {
                    user_id : $(this).data('user-id'),
                    product_id : $(this).val()
                },
                dataType: 'json',
                success: function(feedback) {
                    if (feedback.unchecked) {
                        getCartTotal();
                    }
                }
            })
        }
    });

    $('input[name="payment_type"]').on('change', function() {
        var selectedValue = $('input[name="payment_type"]:checked').val();
        if (selectedValue === '1') {
            console.log('COD selected');
        } else if (selectedValue === '2') {
            console.log('GCash selected');
            
        }
    });
})