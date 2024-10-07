$.fn.dataTable.moment('MMMM D, YYYY');

$(document).ready(function () {
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

    function getOrders() {
        $.ajax({
            url: '/get-orders',
            method: 'POST',
            dataType: 'html',
            success: function (html) {
                $('#order-table-content').html(html);
            }
        });
    }

    function paidStatus() {
        var order_ref = $('#order_ref').val();
        $.ajax({
            url: '/paid-status',
            method: 'POST',
            data: {
                order_ref: order_ref
            },
            dataType: 'text',
            success: function (html) {
                $('#paid-status').html(html);
            }
        })
    }

    function orderStatus() {
        var order_ref = $('#order_ref').val();
        $.ajax({
            url: '/order-status',
            method: 'POST',
            data: {
                order_ref: order_ref
            },
            dataType: 'json',
            success: function (json) {
                $('#order-status').html(json.html);
                if (json.status === 'delivered' || json.status === 'delivering') {
                    $('#cancel-order').prop('disabled', true);
                } else {
                    $('#cancel-order').prop('disabled', false);
                }
            }
        })
    }

    function getProof() {
        var order_ref = $('#order_ref').val();

        $.ajax({
            url: '/get-proof',
            method: 'POST',
            data: {
                order_ref: order_ref
            },
            dataType: 'html',
            success: function (html) {
                $('#proof-content').html(html);
            }
        });
    }

    $('#order-table').DataTable({
        order: [
            [2, 'desc']
        ]
    });

    $(document).on('click', '.viewOrder', function () {
        $('#orderLabel').html('<strong>' + $(this).data('order-ref') + '</strong>');
        $('#order_ref').val($(this).data('order-ref'));
        paidStatus();
        orderStatus();
        $('#order-modal').modal('show');
    });

    $(document).on('click', '#paid-btn', function (event) {
        event.preventDefault();
        $('#paid-modal-warning').html('Do you want to approve the payment of this order <span class="text-primary">' + $('#order_ref').val() + '</span>?');
        $('#order-modal').modal('hide');
        $('#paid-modal').modal('show');
    });

    $(document).on('click', '#paid-modal-close', function (event) {
        event.preventDefault();
        $('#paid-modal').modal('hide');
        $('#order-modal').modal('show');
    });

    $(document).on('click', '#delivering-btn', function (event) {
        event.preventDefault();
        $('#delivering-modal-warning').html('Are you sure you want to set the order status of this order <span class="text-primary">' + $('#order_ref').val() + '</span> to <span class="text-primary">DELIVERING</span>?');
        $('#order-modal').modal('hide');
        $('#delivering-modal').modal('show');
    });

    $(document).on('click', '#delivering-modal-close', function (event) {
        event.preventDefault();
        $('#delivering-modal').modal('hide');
        $('#order-modal').modal('show');
    });

    $(document).on('click', '#delivered-btn', function (event) {
        event.preventDefault();
        $('#delivered-modal-warning').html('Are you sure you want to set the order status of this order <span class="text-primary">' + $('#order_ref').val() + '</span> to <span class="text-success">DELIVERED</span>?');
        $('#order-modal').modal('hide');
        $('#delivered-modal').modal('show');
    });

    $(document).on('click', '#delivered-modal-close', function (event) {
        event.preventDefault();
        $('#delivered-modal').modal('hide');
        $('#order-modal').modal('show');
    });

    $('.print-receipt').on('click', function () {
        var order_ref = $(this).data('order-ref');
        var printUrl = '/print-receipt/' + order_ref;
        window.open(printUrl, '_blank');
    });

    $('#confirm-paid').on('click', function (event) {
        event.preventDefault();
        var order_ref = $('#order_ref').val();
        var paid = $('#paid').val();

        $.ajax({
            url: '/update-paid',
            method: 'POST',
            data: {
                order_ref: order_ref,
                paid: paid
            },
            success: function () {
                paidStatus();
                orderStatus();
                getOrders();
                $('#paid-modal').modal('hide');
                $('#order-modal').modal('show');
            }
        });
    });

    $('#confirm-delivering').on('click', function (event) {
        event.preventDefault();
        var order_ref = $('#order_ref').val();
        var status = $('#delivering').val();

        $.ajax({
            url: '/update-status',
            method: 'POST',
            data: {
                order_ref: order_ref,
                status: status
            },
            success: function () {
                orderStatus();
                getOrders();
                $('#delivering-modal').modal('hide');
                $('#order-modal').modal('show');
            }
        });
    });

    $('#confirm-delivered').on('click', function (event) {
        event.preventDefault();
        var order_ref = $('#order_ref').val();
        var status = $('#delivered').val();

        $.ajax({
            url: '/update-status',
            method: 'POST',
            data: {
                order_ref: order_ref,
                status: status
            },
            success: function () {
                orderStatus();
                getOrders();
                $('#delivered-modal').modal('hide');
                $('#order-modal').modal('show');
            }
        });
    });

    $('#view-proof').on('click', function () {
        getProof();
        $('#proof-modal').modal('show');
        $('#order-modal').modal('hide');
    });

    $('#proof-modal-close').on('click', function () {
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

    const $tableContainer = $('#draggable-table-container');
    let isDown = false;
    let startX;
    let scrollLeft;

    $tableContainer.on('mousedown', function (e) {
        isDown = true;
        $tableContainer.addClass('active');
        startX = e.pageX - $tableContainer.offset().left;
        scrollLeft = $tableContainer.scrollLeft();
    });

    $tableContainer.on('mouseleave', function () {
        isDown = false;
        $tableContainer.removeClass('active');
    });

    $tableContainer.on('mouseup', function () {
        isDown = false;
        $tableContainer.removeClass('active');
    });

    $tableContainer.on('mousemove', function (e) {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - $tableContainer.offset().left;
        const walk = (x - startX) * 3; // Scroll-fast
        $tableContainer.scrollLeft(scrollLeft - walk);
    });

    $('#printButton').click(function () {
        var content = $('#printContent').html();
        var header = `<div id="printHeader">
                        <div class="text-center" style="font-size: 12px;">
                            <h5 class="mb-0 fw-semibold" style="font-size: 14px;">FM Odulio's Enterprise & Gen. Merchandise</h5>
                            <p class="mb-0" style="font-size: 10px;">Mc Arthur Hi-way, Poblacion II, Marilao, Bulacan</p>
                            <p class="mb-0" style="font-size: 10px;">fmoduliogenmdse@yahoo.com</p>
                            <p class="mb-0" style="font-size: 10px;">0922-803-3898</p>
                            <h6 class="mt-2 mb-2" style="font-size: 12px;">Order Report</h6>
                        </div>
                        <hr style="margin: 5px 0;">
                    </div>`;

        // Append the header to the content
        $('body').html(header + content);

        // Print the page
        window.print();

        // Restore the original content
        location.reload();
    });
})