$(document).ready(function(){
    function getPOItem () {
        var po_ref = $('#po_ref').val();
        
        $.ajax({
            url: '/get-po-item',
            method: 'POST',
            data: {
                po_ref : po_ref
            },
            dataType: 'json',
            success: function(json) {
                $('#po_item_content').html(json.content);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    }

    function delPOItem (po_ref, product) {
        $.ajax({
            url: '/del-po-item',
            method: 'POST',
            data: {
                po_ref : po_ref,
                product : product
            },
            dataType: 'json',
            success: function(json) {
                if (json.success) {
                    getPOItem();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    }

    getPOItem();

    $('#product').select2({
        width: '100%',
        placeholder: 'Select a product',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $('#po_item').submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '/add-po-item',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json) {
                console.log(json);
                if (json.failed) {
                    Swal.fire({
                        title: "Oops!",
                        text: "The product is already added.",
                        icon: "error"
                    });
                }

                if (json.success) {
                    getPOItem();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    });

    $(document).on('click', '.del', function(){
        var po_ref = $(this).data('po-ref');
        var product = $(this).data('product-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to remove this product?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                delPOItem(po_ref, product);
            } else if (result.dismiss === Swal.DismissReason.cancel) {

            }
        });
    });
});