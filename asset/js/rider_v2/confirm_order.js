$(document).ready(function() {
    function getOrderRefFromPath() {
        // Get the current URL path
        const path = window.location.pathname;
        // Split the path by '/' and get the last segment
        const segments = path.split('/');
        return segments[segments.length - 1]; // Last segment is the order_ref
    }

    function formatCurrency(amount) {
        // Convert amount to a number and format it as currency
        return '₱' + parseFloat(amount || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function fetchOrderData(orderRef) {
        $.ajax({
            url: '/model-confirmedOrder-details', // Update with the path to your PHP file
            method: 'GET',
            data: { order_ref: orderRef },
            success: function(response) {
                if (response) {
                    console.log('Fetched Order Data:', response); // Log the entire response

                    // Use nullish coalescing operator to default to 0 if null or undefined
                    const orderPrice = response.gross ?? 0;
                    const discount = response.discount ?? 0;
                    const deliveryFee = response.delivery_fee ?? 0;

                    $('#order-ref').text(`Order Ref: ${response.order_ref}`);
                    $('#order-price').text(`Order Price: ${formatCurrency(orderPrice)}`);
                    $('#order-discount').text(`Discount: ${formatCurrency(discount)}`);
                    $('#order-delivery-fee').text(`Delivery Fee: ${formatCurrency(deliveryFee)}`);

                    // Optional: Add more console logs if needed
                    console.log('Order Ref:', response.order_ref);
                    console.log('Order Price:', formatCurrency(orderPrice));
                    console.log('Discount:', formatCurrency(discount));
                    console.log('Delivery Fee:', formatCurrency(deliveryFee));
                } else {
                    // Handle the case where no data is returned
                    $('#order-ref').text('Order Ref: N/A');
                    $('#order-price').text('Order Price: ₱0.00');
                    $('#order-discount').text('Discount: ₱0.00');
                    $('#order-delivery-fee').text('Delivery Fee: ₱0.00');
                }
            },
            error: function() {
                console.error('Failed to fetch order data');
                // Set default values on error
                $('#order-ref').text('Order Ref: N/A');
                $('#order-price').text('Order Price: ₱0.00');
                $('#order-discount').text('Discount: ₱0.00');
                $('#order-delivery-fee').text('Delivery Fee: ₱0.00');
            }
        });
    }

    const orderRef = getOrderRefFromPath();
    console.log('Extracted order_ref:', orderRef); // Log extracted order_ref
    if (orderRef) {
        fetchOrderData(orderRef);
    } else {
        // Handle the case where no order_ref is found
        $('#order-ref').text('Order Ref: N/A');
        $('#order-price').text('Order Price: ₱0.00');
        $('#order-discount').text('Discount: ₱0.00');
        $('#order-delivery-fee').text('Delivery Fee: ₱0.00');
        console.error('No order_ref found in the URL path');
    }
});
