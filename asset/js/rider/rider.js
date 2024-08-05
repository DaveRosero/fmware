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
    
                // Initialize DataTable
                $('#orders-table').DataTable({
                    data: response.map(order => [
                        order.order_ref,
                        order.firstname + ' ' + order.lastname,
                        formatDateTime(order.date),
                        order.transaction_type_name || '',
                        order.payment_type_name || '',
                        '₱' + parseFloat(order.gross).toFixed(2),
                        '₱' + parseFloat(order.net).toFixed(2),
                        '₱' + parseFloat(order.paid).toFixed(2),
                        '<span class="badge ' + getStatusBadgeClass(order.status) + '">' + order.status + '</span>',
                        '<button class="btn btn-primary view-order-btn" data-order-id="' + order.id + '">View</button>'
                    ]),
                    columns: [
                        { title: 'Order Ref' },
                        { title: 'Customer' },
                        { title: 'Date' },
                        { title: 'Transaction Type' },
                        { title: 'Payment Type' },
                        { title: 'Gross Amount' },
                        { title: 'Net Amount' },
                        { title: 'Paid Amount' },
                        { title: 'Status' },
                        { title: 'Actions' }
                    ],
                    order: [[2, "desc"]], // Order by date descending
                    destroy: true // Destroy the existing DataTable instance to recreate it
                });
    
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
                
                $('#orders-table').on('click', '.view-order-btn', function () {
                    var orderId = $(this).data('order-id');
                    // Implement view order details functionality here
                    $.ajax({
                        url: "/path-to-order-details-script",
                        method: "GET",
                        data: { order_id: orderId },
                        dataType: "json",
                        success: function (data) {
                            // Handle the response and update the modal or other UI elements
                            console.log("Order Data: ", data);
                            // Update modal or other UI elements here
                        },
                        error: function (xhr, status, error) {
                            console.error("Error fetching order details: ", status, error);
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