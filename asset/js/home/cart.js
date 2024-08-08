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
                if (feedback.product_total) {
                    $('#product-total').text(feedback.product_total);
                }

                if (feedback.checkout_total) {
                    $('#checkout-total').text(feedback.checkout_total);
                }
            }
        });
    }

    function updateMasterCheckbox() {
        var allChecked = $('.cart-checkbox').length === $('.cart-checkbox:checked').length;
        $('#checkAll').prop('checked', allChecked);
    }

    getCartTotal();
    updateMasterCheckbox();

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
    
    // $(document).on('click', '.addQty', function(){
    //     var id = $(this).data('product-id');
    //     var qty = $('.qty_' + id);
    //     var subtotal = $('.subtotal_' + id);
    //     $.ajax({
    //         url: '/add-qty',
    //         method: 'POST',
    //         data: {
    //             id: $(this).data('cart-id')
    //         },
    //         dataType: 'json',
    //         success: function(feedback){
    //             qty.val(feedback.qty);
    //             subtotal.text(feedback.subtotal);
    //             getCartTotal();
    //         }
    //     });
    // });

    // $(document).on('click', '.subQty', function(){
    //     var id = $(this).data('product-id');
    //     var qty = $('.qty_' + id);
    //     var subtotal = $('.subtotal_' + id);

    //     if (qty.val() == 1) {
    //         return;
    //     }

    //     $.ajax({
    //         url: '/sub-qty',
    //         method: 'POST',
    //         data: {
    //             id: $(this).data('cart-id')
    //         },
    //         dataType: 'json',
    //         success: function(feedback){
    //             qty.val(feedback.qty);
    //             subtotal.text(feedback.subtotal);
    //             getCartTotal();
    //         }
    //     });
    // });

    $(document).on('click', '.addQty', function() {
        var productId = $(this).data('product-id');
        var cartId = $(this).data('cart-id');
        var qtyInput = $('input[data-product-id="' + productId + '"]');
        var subtotal = $('.subtotal[data-product-id="' + productId + '"]');
    
        $.ajax({
            url: '/add-qty',
            method: 'POST',
            data: {
                id: cartId
            },
            dataType: 'json',
            success: function(feedback) {
                console.log(feedback);
                qtyInput.val(feedback.qty);
                subtotal.text(feedback.subtotal);
                getCartTotal();
            }
        });
    });
    
    $(document).on('click', '.subQty', function() {
        var productId = $(this).data('product-id');
        var cartId = $(this).data('cart-id');
        var qtyInput = $('input[data-product-id="' + productId + '"]');
        var subtotal = $('.subtotal[data-product-id="' + productId + '"]');
    
        if (qtyInput.val() == 1) {
            return;
        }
    
        $.ajax({
            url: '/sub-qty',
            method: 'POST',
            data: {
                id: cartId
            },
            dataType: 'json',
            success: function(feedback) {
                console.log(feedback);
                qtyInput.val(feedback.qty);
                subtotal.text(feedback.subtotal);
                getCartTotal();
            }
        });
    });

    $(document).on('change', '.qty-input', function() {
        var productId = $(this).data('product-id');
        var cartId = $(this).data('cart-id');
        var qty = $(this).val();
        var subtotal = $('.subtotal[data-product-id="' + productId + '"]');
    
        if (qty < 1) {
            qty = 1;
            $(this).val(qty);
        }
    
        $.ajax({
            url: '/cart-update-qty',
            method: 'POST',
            data: {
                id: cartId,
                qty: qty
            },
            dataType: 'json',
            success: function(feedback) {
                console.log(feedback);
                subtotal.text(feedback.subtotal);
                getCartTotal();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
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
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
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
            });

            updateMasterCheckbox();
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
            });

            updateMasterCheckbox();
        }
    });

    $('input[name="payment_type"]').on('change', function() {
        var selectedValue = $('input[name="payment_type"]:checked').val();
        if (selectedValue === '1') {
            console.log('COD selected');
            $('#address').prop('disabled', false);
            $('#address').trigger('change');
        } else if (selectedValue === '2') {
            console.log('GCash selected');
            $('#address').prop('disabled', false);
            $('#address').trigger('change');
        } else if (selectedValue === '4') {
            console.log('Pikcup selected');
            $('#delivery-fee').text('-');
            $('#delivery-fee-value').val(0);
            $('#address').val('');
            $('#address').trigger('change');
            $('#address').prop('disabled', true);
        }
    });

    $('#checkAll').click(function() {
        var isChecked = this.checked;
        $('.cart-checkbox').each(function() {
            var $checkbox = $(this);
            if (isChecked && !$checkbox.is(':checked')) {
                // If master checkbox is checked and individual checkbox is not checked, trigger click
                $checkbox.click();
            } else if (!isChecked && $checkbox.is(':checked')) {
                // If master checkbox is unchecked and individual checkbox is checked, trigger click
                $checkbox.click();
            }
        });
    });

    $('.del-cart').click(function(){
        var product_id = $(this).data('product-id');
        var cart_id = $(this).data('cart-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to remove this product from your cart?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/del-cart-item',
                    method: 'POST',
                    data: {
                        product_id : product_id,
                        cart_id : cart_id
                    },
                    dataType: 'json',
                    success: function(json) {
                        if(json.redirect) {
                            window.location.href = json.redirect;
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log("Error:", textStatus, errorThrown);
                        console.log("Response:", jqXHR.responseText);
                    }
                });
            }
        });
    })
})