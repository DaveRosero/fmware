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
                $('#grand_total').text('TOTAL: ₱' + json.grand_total);
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

    function updateQty (po_ref, id, qty, $element) {
        $.ajax({
            url: '/qty-po-item',
            method: 'POST',
            data: {
                po_ref : po_ref,
                id : id,
                qty : qty
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
        })
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
                $('#grand_total').text('TOTAL: ₱' + json.order_total);
                console.log(json);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    }

    function updateRemarks (po_ref, remarks) {
        $.ajax({
            url: '/po-remarks',
            method: 'POST',
            data: {
                po_ref : po_ref,
                remarks : remarks
            },
            dataType: 'json',
            success: function(json) {
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    }

    function updateUnit (po_ref, id, unit) {
        $.ajax({
            url: '/unit-po-item',
            method: 'POST',
            data: {
                po_ref : po_ref,
                id : id,
                unit : unit
            },
            dataType: 'json',
            success: function(json) {
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    }

    function savePO (po_ref) {
        $.ajax({
            url: '/save-po',
            method: 'POST',
            data: {
                po_ref : po_ref
            },
            dataType: 'json',
            success: function(json) {
                if (json.empty) {
                    Swal.fire({
                        title: 'Please add at least one (1) product.',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonText: 'Okay',
                        allowOutsideClick: true,
                    })
                    return;
                }
                
                if (json.redirect) {
                    Swal.fire({
                        title: 'Purchase Order Saved!',
                        icon: 'success',
                        confirmButtonText: 'Okay',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = json.redirect;
                        }
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    }

    function deletePO (po_ref) {
        $.ajax({
            url: '/delete-po',
            method: 'POST',
            data: {
                po_ref : po_ref
            },
            dataType: 'json',
            success: function(json) {
                if (json.redirect) {
                    Swal.fire({
                        title: 'Purchase Order Deleted!',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'Okay',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = json.redirect;
                        }
                    });
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

    $(document).on('change', '.poi-qty', function(){
        var id = $(this).data('product-id');
        var po_ref = $(this).data('po-ref');
        var qty = $(this).val();

        if (qty <= 0) {
            Swal.fire({
                title: "Oops!",
                text: "Please enter one (1) or more in Quantity.",
                icon: "warning"
            });
            $(this).val(1);
            return;
        }

        updateQty(po_ref, id, qty, $(this));
    });

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

    $(document).on('change', '.poi-unit', function(){
        var id = $(this).data('product-id');
        var po_ref = $(this).data('po-ref');
        var unit = $(this).val();

        updateUnit(po_ref, id, unit);
    });

    $(document).on('change', '#remarks', function(){
        var po_ref = $(this).data('po-ref');
        var remarks = $(this).val();

        updateRemarks (po_ref, remarks);
    });

    $('#save').click(function(){
        var po_ref = $(this).data('po-ref');
        var filled = true;

        $('.poi-unit').each(function(){
            if ($(this).val().trim() === "") {
              filled = false;
            }
        });

        if (!filled) {
            Swal.fire({
                title: 'Please fill in all Unit.',
                icon: 'warning',
                confirmButtonText: 'Okay'
            });
            return;
        }

        Swal.fire({
            title: 'Saving Purchase Order',
            text: "This action is irreversible, do you want to continue?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'No, cancel!',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                savePO(po_ref);
            }
        });
    });

    $('#delete').click(function(){
        var po_ref = $(this).data('po-ref');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this Purchase Order?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                deletePO(po_ref);
            } else if (result.dismiss === Swal.DismissReason.cancel) {

            }
        });
    });
});