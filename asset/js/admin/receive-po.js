$(document).ready(function(){
    function getPendingPOItems () {
        var po_ref = $('#po_ref').val();

        $.ajax({
            url: '/receive-po-item',
            method: 'POST',
            data: {
                po_ref : po_ref
            },
            dataType: 'json',
            success: function(json) {
                console.log(json);
                $('#po_item_content').html(json.content);
                $('#received_total').text('₱' + json.received_total);
                $('#grand_total').text('GRAND TOTAL: ₱' + json.grand_total);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    }

    function updatePrice (po_ref, id, price, $element) {
        $.ajax({
            url: '/price-po-item',
            method: 'POST',
            data: {
                po_ref : po_ref,
                id : id,
                price : price
            },
            dataType: 'json',
            success: function(json) {
                $element.closest('tr').find('td:eq(5)').text('₱' + json.total);
                $element.closest('tr').find('td:eq(7)').text('₱' + json.amount);
                $('#received_total').text('₱' + json.received_total);
                $('#grand_total').text('GRAND TOTAL: ₱' + json.grand_total);
                $('#order_total').text('₱' + json.order_total);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    }

    function updateReceived (po_ref, id, received, $element) {
        $.ajax({
            url: '/update-receive-item',
            method: 'POST',
            data: {
                po_ref : po_ref,
                id : id,
                received : received
            },
            dataType: 'json',
            success: function(json) {
                if (json.invalid_received) {
                    Swal.fire({
                        title: "Oops!",
                        text: "You can't enter amount greater than requested Quantity.",
                        icon: "warning"
                    });
                    $element.val(0);
                    return;
                }

                $element.closest('tr').find('td:eq(7)').text('₱' + json.amount);
                $('#received_total').text('₱' + json.received_total);
                $('#grand_total').text('GRAND TOTAL: ₱' + json.grand_total);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    }

    function updateShipping (po_ref, shipping) {
        $.ajax({
            url: '/update-po-shipping',
            method: 'POST',
            data: {
                po_ref : po_ref,
                shipping : shipping
            },
            dataType: 'json',
            success: function(json) {
                $('#grand_total').text('GRAND TOTAL: ₱' + json.grand_total);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    }

    function updateOthers(po_ref, others) {
        $.ajax({
            url: '/update-po-others',
            method: 'POST',
            data: {
                po_ref : po_ref,
                others : others
            },
            dataType: 'json',
            success: function(json) {
                $('#grand_total').text('GRAND TOTAL: ₱' + json.grand_total);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    }

    function completePO (po_ref) {
        $.ajax({
            url: '/complete-po',
            method: 'POST',
            data: {
                po_ref : po_ref
            },
            dataType: 'json',
            success: function(json) {
                if (json.redirect) {
                    window.location.href = json.redirect;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
            
        })
    }

    getPendingPOItems();

    $(document).on('change', '.poi-price', function(){
        var id = $(this).data('product-id');
        var po_ref = $(this).data('po-ref');
        var price = $(this).val();

        if (price < 0) {
            Swal.fire({
                title: "Oops!",
                text: "Please enter a positive number on Price.",
                icon: "warning"
            });
            $(this).val(0);
            return;
        }

        updatePrice(po_ref, id, price, $(this));
    });

    $(document).on('change', '.poi-received', function(){
        var id = $(this).data('product-id');
        var po_ref = $(this).data('po-ref');
        var received = $(this).val();

        updateReceived(po_ref, id, received, $(this));
    });

    $(document).on('change', '#shipping', function(){
        var po_ref = $('#po_ref').val();
        var shipping = $(this).val();

        if (shipping < 0) {
            Swal.fire({
                title: "Oops!",
                text: "Please enter a positive number on Shipping.",
                icon: "warning"
            });
            $(this).val(0);
            return;
        }

        updateShipping (po_ref, shipping);
    });

    $(document).on('change', '#others', function(){
        var po_ref = $('#po_ref').val();
        var others = $(this).val();

        if (others < 0) {
            Swal.fire({
                title: "Oops!",
                text: "Please enter a positive number on Others.",
                icon: "warning"
            });
            $(this).val(0);
            return;
        }

        updateOthers (po_ref, others);
    });

    $('#save').click(function(){
        var po_ref = $(this).data('po-ref');

        completePO(po_ref);
    });
});