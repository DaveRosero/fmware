$(document).ready(function(){
    function getOrderDetails (order_ref) {
        $.ajax({
            url: '/order-details',
            method: 'POST',
            data: {
                order_ref : order_ref
            },
            dataType: 'html',
            success: function(feedback) {
                $('#order-details').html(feedback);
                $('#loadingIndicator').hide();
            }
        });
    }
    

    function getQrCode(order_ref) {
        $.ajax({
            url: '/get-qr',
            method: 'POST',
            data: {
                order_ref: order_ref
            },
            dataType: 'text',
            success: function(feedback) {
                $('#loadingIndicator').hide();
                $('#qr-code').html('<p>Present this QR Code to our delivery driver upon receiving your order.</p>');
                jQuery('#qr-code').qrcode(feedback);
            }
        });
    }

    $('#purchase-table').DataTable({
        order: [
            [2, 'desc']
        ]
    });

    $('.viewOrder').on('click', function() {
        var order_ref = $(this).data('order-ref');
        $('#viewOrderModal').modal('show');
        $('#loadingIndicator').show();
        getQrCode(order_ref);
        getOrderDetails(order_ref);
    });

    $('#viewOrderModal').on('hidden.bs.modal', function(){
        $('#order-details').empty();
        $('#qr-code').empty();
    });

    $('#upload-proof-form').submit(function(event){
        event.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: '/upload-proof',
            method: 'POST',
            contentType: false,
            processData: false,
            data: formData,
            dataType: 'json',
            success: function(feedback){
                window.location.href = feedback.redirect;
            }
        });
    });
})