$(document).ready(function(){
    function paidOpt (paid) {
        var select = $('#paid');
        select.empty();

        if (paid == 'paid') {
            select.append('<option value="paid" selected>PAID</option>');
            select.append('<option value="unpaid">UNPAID</option>');
        }

        if (paid == 'unpaid') {
            select.append('<option value="paid">PAID</option>');
            select.append('<option value="unpaid" selected>UNPAID</option>');
        }
    }

    function statusOpt (status) {
        var select = $('#status');
        select.empty();

        if (status == 'pending') {
            select.append('<option value="delivered">DELIVERED</option>');
            select.append('<option value="delivering">DELIVERING</option>');
            select.append('<option value="pending" selected>PENDING</option>');
        }

        if (status == 'delivering') {
            select.append('<option value="delivered">DELIVERED</option>');
            select.append('<option value="delivering" selected>DELIVERING</option>');
            select.append('<option value="pending">PENDING</option>');
        }

        if (status == 'delivered') {
            select.append('<option value="delivered" selected>DELIVERED</option>');
            select.append('<option value="delivering">DELIVERING</option>');
            select.append('<option value="pending">PENDING</option>');
        }
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
        paidOpt($(this).data('paid'));
        statusOpt($(this).data('status'));
        $('#order-modal').modal('show');
    });

    $('#order-form').on('submit', function(event){
        event.preventDefault();
        console.log('form submitted');

        $.ajax({
            url: '/fmware/update-order',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                console.log('ajax success');
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
            }
        });
    })
})