$(document).ready(function() {
    function fetchOrders() {
        $.ajax({
            url: '/fetch-rider-orders', // Replace with actual endpoint
            method: 'GET',
            dataType: 'json',
            success: function (response) {
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
                        '<button class="btn btn-primary view-order-btn" data-order-id="' + order.id + '">View</button>'
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

                $('#orders-table').on('click', '.view-order-btn', function () {
                    var orderRef = $(this).data('order-ref');
                    $.ajax({
                        url: "/fetch-order-items", // Endpoint to fetch order items
                        method: "GET",
                        data: { order_ref: orderRef },
                        dataType: "json",
                        success: function (data) {
                            if (Array.isArray(data)) {
                                var modalBody = $('#orderItemsModal .modal-body');
                                var tableBody = $('#order-items-table tbody');
                                tableBody.empty(); // Clear existing content
                
                                if (data.length > 0) {
                                    data.forEach(function(item) {
                                        var totalPrice = (item.qty * item.unit_price).toFixed(2);
                                        tableBody.append('<tr><td>' + item.product_name + '</td><td>' + item.qty + '</td><td>₱' + parseFloat(item.unit_price).toFixed(2) + '</td><td>₱' + totalPrice + '</td></tr>');
                                    });
                                } else {
                                    tableBody.html('<tr><td colspan="4" class="text-center">No items found for this order.</td></tr>');
                                }
                
                                $('#orderItemsModal').modal('show'); // Show the modal
                            } else {
                                console.error("Unexpected response format:", data);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error fetching order items:", status, error);
                            console.error("Response text:", xhr.responseText); // Log response text for debugging
                        }
                    });
                });
                
            },
            error: function (xhr, status, error) {
                console.error('Error fetching orders:', error);
            }
        });
    }
    fetchOrders();
});
