$(document).ready(function() {
    function fetchOrders() {
        $.ajax({
            url: '/fetch-rider-orders', // Replace with actual endpoint
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                console.log('Orders Response:', response);
                // Function to format date
                function formatDateTime(dateTime) {
                    const date = new Date(dateTime);
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };
                    return date.toLocaleDateString('en-US', options);
                }

                // Function to get status badge class
                function getStatusBadgeClass(status) {
                    switch(status.toLowerCase()) {
                        case 'delivering':
                            return 'badge text-bg-primary'; // Class for delivering status
                        case 'delivered':
                            return 'badge text-bg-success'; // Class for delivered status
                        case 'pending':
                            return 'badge text-bg-warning'; // Class for pending status
                        case 'cancelled':
                            return 'badge text-bg-danger'; // Class for cancelled status
                        default:
                            return 'badge text-bg-secondary'; // Default class
                    }
                }

                // Function to get paid status badge class
                function getPaidStatusBadgeClass(paid) {
                    switch(paid.toLowerCase()) {
                        case 'paid':
                            return 'badge text-bg-success'; // Class for paid status
                        case 'unpaid':
                            return 'badge text-bg-danger'; // Class for unpaid status
                        default:
                            return 'badge text-bg-secondary'; // Default class
                    }
                }

                // Initialize DataTable
                $('#orders-table').DataTable({
                    data: response.map(order => [
                        order.order_ref,
                        order.firstname + ' ' + order.lastname,
                        formatDateTime(order.date),
                        order.transaction_type_name || '',
                        order.payment_type_name || '',
                        '₱' + parseFloat(order.gross).toFixed(2),
                        '<span class="' + getPaidStatusBadgeClass(order.paid) + '">' + order.paid + '</span>',
                        '<span class="badge ' + getStatusBadgeClass(order.status) + '">' + order.status + '</span>',
                        '<button class="btn btn-primary view-order-btn" data-order-ref="' + order.order_ref + '">View</button>'
                    ]),
                    columns: [
                        { title: 'Order Ref' },
                        { title: 'Customer' },
                        { title: 'Date' },
                        { title: 'Transaction' },
                        { title: 'Payment' },
                        { title: 'Total' },
                        { title: 'Paid' },
                        { title: 'Status' },
                        { title: 'Actions' }
                    ],
                    order: [[2, "desc"]], // Order by date descending
                    destroy: true // Destroy the existing DataTable instance to recreate it
                });
                     
            },
            error: function (xhr, status, error) {
                console.error('Error fetching orders:', error);
            }
        });
    }
    function fetchOrderItems(orderRef) {
        $.ajax({
            url: '/fetch-order-items', // Replace with actual endpoint
            method: 'GET',
            data: { order_ref: orderRef }, // Ensure correct parameter name
            dataType: 'json',
            success: function (response) {
                let orderItemsTable = $('#orderItems-table tbody');
                orderItemsTable.empty(); // Clear previous items
                response.forEach(item => {
                    orderItemsTable.append(`
                        <tr class="text-center">
                            <td>${item.product_name}</td>
                            <td>${item.variant_name || 'N/A'}</td>
                            <td>${item.unit_name || 'N/A'}</td>
                            <td>₱${parseFloat(item.price).toFixed(2)}</td>
                            <td>${item.qty}</td>
                            <td>₱${(parseFloat(item.price) * item.qty).toFixed(2)}</td>
                        </tr>
                    `);
                });
                $('#exampleModal').modal('show');
            },
            error: function (xhr, status, error) {
                console.error('Error fetching order items:', error);
                console.error('Response text:', xhr.responseText); // Log response text
            }
        });
    }
    

    $(document).on('click', '.view-order-btn', function() {
        const orderRef = $(this).data('order-ref');
        console.log('Order Ref:', orderRef); // Debugging line
        if (orderRef) {
            fetchOrderItems(orderRef);
        } else {
            console.error('Order reference is not provided');
        }
    });
    
    
    
    fetchOrders();
});
