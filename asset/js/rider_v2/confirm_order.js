$(document).ready(function() {
    function getOrderRefFromPath() {
        const path = window.location.pathname;
        const segments = path.split('/');
        return segments[segments.length - 1];
    }

    function formatCurrency(amount) {
        return '₱' + parseFloat(amount || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function fetchOrderData(orderRef) {
        $.ajax({
            url: '/model-confirmedOrder-details',
            method: 'GET',
            data: { order_ref: orderRef },
            success: function(response) {
                if (response) {
                    const orderPrice = response.gross ?? 0;
                    const discount = response.discount ?? 0;
                    const deliveryFee = response.delivery_fee ?? 0;

                    const orderTotal = orderPrice - deliveryFee;
                    const totalPrice = orderTotal - discount + deliveryFee;

                    $('#order-ref').text(`Order Ref: ${response.order_ref}`);
                    $('#order-price').text(`Order Price: ${formatCurrency(orderTotal)}`);
                    $('#order-discount').text(`Discount: ${formatCurrency(discount)}`);
                    $('#order-delivery-fee').text(`Delivery Fee: ${formatCurrency(deliveryFee)}`);
                    $('#total-price').text(`Total Price: ${formatCurrency(totalPrice)}`);
                } else {
                    $('#order-ref').text('Order Ref: N/A');
                    $('#order-price').text('Order Price: ₱0.00');
                    $('#order-discount').text('Discount: ₱0.00');
                    $('#order-delivery-fee').text('Delivery Fee: ₱0.00');
                    $('#total-price').text('Total Price: ₱0.00');
                }
            },
            error: function() {
                console.error('Failed to fetch order data');
                $('#order-ref').text('Order Ref: N/A');
                $('#order-price').text('Order Price: ₱0.00');
                $('#order-discount').text('Discount: ₱0.00');
                $('#order-delivery-fee').text('Delivery Fee: ₱0.00');
                $('#total-price').text('Total Price: ₱0.00');
            }
        });
    }

    function updateChange() {
        const totalPrice = parseFloat($('#total-price').text().replace('Total Price: ₱', '').replace(',', '')) || 0;
        const cashReceived = parseFloat($('input[type="number"]').val()) || 0;
        const change = cashReceived - totalPrice;

        if (cashReceived >= totalPrice) {
            $('#order-change').text(`Change: ${formatCurrency(change)}`);
            $('#delivered-btn').prop('disabled', false);
        } else {
            $('#order-change').text('Change: ₱0.00');
            $('#delivered-btn').prop('disabled', true);
        }
    }

    function handleDelivery() {
        const paymentMethod = $('input[name="flexRadioDefault"]:checked').val();
        const orderRef = getOrderRefFromPath();
        const cashReceived = parseFloat($('input[type="number"]').val()) || 0;

        if (!orderRef || !cashReceived) {
            alert('Please ensure all information is provided.');
            return;
        }

        $.ajax({
            url: '/model-processPayment', // Update with the path to your PHP file
            method: 'POST',
            data: {
                order_ref: orderRef,
                status: 'delivered',
                payment_type: paymentMethod,
                cash_received: cashReceived
            },
            success: function(response) {
                if (response.success) {
                    alert('Order status updated successfully.');
                    // Redirect or update the UI as needed
                } else {
                    alert('Failed to update order status. Please try again.');
                }
            },
            error: function() {
                console.error('Failed to update order status');
                alert('Failed to update order status. Please try again.');
            }
        });
    }

    $('input[type="number"]').on('input', function() {
        updateChange();
    });

    $('#delivered-btn').on('click', function() {
        handleDelivery();
    });

    const orderRef = getOrderRefFromPath();
    if (orderRef) {
        fetchOrderData(orderRef);
    } else {
        $('#order-ref').text('Order Ref: N/A');
        $('#order-price').text('Order Price: ₱0.00');
        $('#order-discount').text('Discount: ₱0.00');
        $('#order-delivery-fee').text('Delivery Fee: ₱0.00');
        $('#total-price').text('Total Price: ₱0.00');
    }
});
