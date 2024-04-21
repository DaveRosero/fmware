$(document).ready(function(){
    // function paidOpt (paid) {
    //     var select = $('#paid');
    //     select.empty();

    //     if (paid == 'paid') {
    //         select.append('<option value="paid" selected>PAID</option>');
    //         select.append('<option value="unpaid">UNPAID</option>');
    //     }

    //     if (paid == 'unpaid') {
    //         select.append('<option value="paid">PAID</option>');
    //         select.append('<option value="unpaid" selected>UNPAID</option>');
    //     }
    // }

    // function statusOpt (status) {
    //     var select = $('#status');
    //     select.empty();

    //     if (status == 'pending') {
    //         select.append('<option value="delivered">DELIVERED</option>');
    //         select.append('<option value="delivering">DELIVERING</option>');
    //         select.append('<option value="pending" selected>PENDING</option>');
    //     }

    //     if (status == 'delivering') {
    //         select.append('<option value="delivered">DELIVERED</option>');
    //         select.append('<option value="delivering" selected>DELIVERING</option>');
    //         select.append('<option value="pending">PENDING</option>');
    //     }

    //     if (status == 'delivered') {
    //         select.append('<option value="delivered" selected>DELIVERED</option>');
    //         select.append('<option value="delivering">DELIVERING</option>');
    //         select.append('<option value="pending">PENDING</option>');
    //     }
    // }

    function getOrders () {
        $.ajax({
            url: '/get-orders',
            method: 'POST',
            dataType: 'html',
            success: function(html){
                $('#order-table-content').html(html);
            }
        });
    }

    function paidStatus () {
        var order_ref = $('#order_ref').val();
        $.ajax({
            url: '/paid-status',
            method: 'POST',
            data: {
                order_ref : order_ref
            },
            dataType: 'text',
            success: function(html){
                $('#paid-status').html(html);
            }
        })
    }

    function orderStatus () {
        var order_ref = $('#order_ref').val();
        $.ajax({
            url: '/order-status',
            method: 'POST',
            data: {
                order_ref : order_ref
            },
            dataType: 'json',
            success: function(json){
                $('#order-status').html(json.html);
                if (json.status === 'delivered' || json.status === 'delivering') {
                    $('#cancel-order').prop('disabled', true);
                }else {
                    $('#cancel-order').prop('disabled', false);
                }
            }
        })
    }

    function getProof () {
        var order_ref = $('#order_ref').val();
        
        $.ajax({
            url: '/get-proof',
            method: 'POST',
            data: {
                order_ref : order_ref
            },
            dataType: 'html',
            success: function(html){
                $('#proof-content').html(html);
            }
        });
    }

    $('#order-table').DataTable({
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i>',
                className: 'btn btn-secondary',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        order: [
            [2, 'desc']
        ],
        initComplete: function () {
            var dataTableButtons = $('.dt-buttons');
            $('#printButtonContainer').append(dataTableButtons);
        }
    });

    $(document).on('click', '.viewOrder', function(){
        $('#orderLabel').html('<strong>' + $(this).data('order-ref') + '</strong>');
        $('#order_ref').val($(this).data('order-ref'));
        paidStatus();
        orderStatus();
        $('#order-modal').modal('show');
    });

    $(document).on('click', '#paid-btn', function(event){
        event.preventDefault();
        $('#paid-modal-warning').html('Are you sure you want to set the paid status of this order <span class="text-primary">' + $('#order_ref').val() + '</span> to <span class="text-success">PAID</span>?');
        $('#order-modal').modal('hide');
        $('#paid-modal').modal('show');
    });

    $(document).on('click', '#paid-modal-close', function(event){
        event.preventDefault();
        $('#paid-modal').modal('hide'); 
        $('#order-modal').modal('show');
    });

    $(document).on('click', '#delivering-btn', function(event){
        event.preventDefault();
        $('#delivering-modal-warning').html('Are you sure you want to set the order status of this order <span class="text-primary">' + $('#order_ref').val() + '</span> to <span class="text-primary">DELIVERING</span>?');
        $('#order-modal').modal('hide');
        $('#delivering-modal').modal('show');
    });

    $(document).on('click', '#delivering-modal-close', function(event){
        event.preventDefault();
        $('#delivering-modal').modal('hide'); 
        $('#order-modal').modal('show');
    });

    $(document).on('click', '#delivered-btn', function(event){
        event.preventDefault();
        $('#delivered-modal-warning').html('Are you sure you want to set the order status of this order <span class="text-primary">' + $('#order_ref').val() + '</span> to <span class="text-success">DELIVERED</span>?');
        $('#order-modal').modal('hide');
        $('#delivered-modal').modal('show');
    });

    $(document).on('click', '#delivered-modal-close', function(event){
        event.preventDefault();
        $('#delivered-modal').modal('hide'); 
        $('#order-modal').modal('show');
    });

    $('.print-receipt').on('click', function(){
        var order_ref = $(this).data('order-ref');
        var printUrl = '/print-receipt/' + order_ref;
        window.open(printUrl, '_blank');
    });

    $('#confirm-paid').on('click', function(event){
        event.preventDefault();
        var order_ref = $('#order_ref').val();
        var paid = $('#paid').val();

        $.ajax({
            url: '/update-paid',
            method: 'POST',
            data: {
                order_ref : order_ref,
                paid : paid
            },
            success: function(){
                paidStatus();
                getOrders();
                $('#paid-modal').modal('hide');
                $('#order-modal').modal('show');
            }
        });
    });

    $('#confirm-delivering').on('click', function(event){
        event.preventDefault();
        var order_ref = $('#order_ref').val();
        var status = $('#delivering').val();

        $.ajax({
            url: '/update-status',
            method: 'POST',
            data: {
                order_ref : order_ref,
                status : status
            },
            success: function(){
                orderStatus();
                getOrders();
                $('#delivering-modal').modal('hide');
                $('#order-modal').modal('show');
            }
        });
    });

    $('#confirm-delivered').on('click', function(event){
        event.preventDefault();
        var order_ref = $('#order_ref').val();
        var status = $('#delivered').val();

        $.ajax({
            url: '/update-status',
            method: 'POST',
            data: {
                order_ref : order_ref,
                status : status
            },
            success: function(){
                orderStatus();
                getOrders();
                $('#delivered-modal').modal('hide');
                $('#order-modal').modal('show');
            }
        });
    });

    $('#view-proof').on('click', function(){
        getProof();
        $('#proof-modal').modal('show');
        $('#order-modal').modal('hide');
    });

    $('#proof-modal-close').on('click', function(){
        $('#proof-modal').modal('hide');
        $('#order-modal').modal('show');
    });

    // $('#order-form').on('submit', function(event){
    //     event.preventDefault();
    //     console.log('form submitted');

    //     $.ajax({
    //         url: '/update-order',
    //         method: 'POST',
    //         data: $(this).serialize(),
    //         dataType: 'json',
    //         success: function(feedback){
    //             console.log('ajax success');
    //             if (feedback.redirect) {
    //                 window.location.href = feedback.redirect;
    //             }
    //         }
    //     });
    // })
})