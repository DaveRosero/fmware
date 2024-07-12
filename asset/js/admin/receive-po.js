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
                $('#grand_total').text('TOTAL: ₱' + json.grand_total);
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
                $('#grand_total').text('TOTAL: ₱' + json.grand_total);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
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
});