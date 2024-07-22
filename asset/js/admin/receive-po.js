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
            url: '/actual-price-po',
            method: 'POST',
            data: {
                po_ref : po_ref,
                id : id,
                price : price
            },
            dataType: 'json',
            success: function(json) {
                if (json.not_base_price) {
                    Swal.fire({
                        title: "Oops!",
                        html: "The Actual Price is not equal to the product's Base Price. Do you want to change the SRP?<br><br>Current Base Price: " + json.base_price + "<br>Current Selling Price: " + json.selling_price + "<br><br>New Base Price: " + json.new_base_price,
                        icon: "warning",
                        input: 'text',
                        inputPlaceholder: 'Enter new SRP',
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        footer: 'This action will modify both the Base Price and Selling Price.',
                        preConfirm: (newSrp) => {
                            if (!newSrp) {
                                Swal.showValidationMessage('Please enter a new SRP');
                            } else if (isNaN(newSrp)) {
                                Swal.showValidationMessage('SRP must be a number');
                            }
                            return newSrp;
                        },
                        willClose: () => {
                            // This function will be called when the SweetAlert is closed
                            if (Swal.getCancelButton().classList.contains('swal2-cancel')) {
                                getPendingPOItems();
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const newSrp = result.value;
                            // Process the new SRP here, for example send it to the server
                            console.log("New SRP:", newSrp);
                            // Add your code to handle the new SRP, e.g., an AJAX request to update the price
                        }
                    });
                    return;
                }

                $element.closest('tr').find('td:eq(8)').text('₱' + json.amount);
                $('#received_total').text('₱' + json.received_total);
                $('#grand_total').text('GRAND TOTAL: ₱' + json.grand_total);
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

                $element.closest('tr').find('td:eq(8)').text('₱' + json.amount);
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
                    Swal.fire({
                        title: 'Purchase Order Received!',
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
    
    function hasDecimal(num) {
        return num % 1 !== 0;
    }

    getPendingPOItems();

    $(document).on('change', '.poi-price', function(){
        var id = $(this).data('product-id');
        var po_ref = $(this).data('po-ref');
        var price = $(this).val();

        if (price <= 0) {
            Swal.fire({
                title: "Oops!",
                text: "Please enter a number greater than zero (0) in Price.",
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

        if (received < 0) {
            Swal.fire({
                title: "Oops!",
                text: "Please enter a positive number on Received.",
                icon: "warning"
            });
            $(this).val(0);
            return;
        }

        if (hasDecimal(received)) {
            Swal.fire({
                title: "Oops!",
                text: "Please enter a whole number on Received.",
                icon: "warning"
            });
            $(this).val(0);
            return;
        }

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
        var filled = true;

        $('.poi-price').each(function(){
            if ($(this).val().trim() === "" || $(this).val().trim() === "0") {
                filled = false;
            }
        });

        $('.poi-received').each(function(){
            if ($(this).val().trim() === "" || $(this).val().trim() === "0") {
                filled = false;
            }
        });

        if (!filled) {
            Swal.fire({
                title: 'Please fill in all fields correctly.',
                text: 'Ensure that Price and Received field/s has value/s greater than zero (0).',
                icon: 'warning',
                confirmButtonText: 'Okay'
            });
            return;
        }

        Swal.fire({
            title: 'Receiving Purchase Order',
            text: "Do you want to receive and finalize this Purchase Order?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, receive it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                completePO(po_ref);
            } else if (result.dismiss === Swal.DismissReason.cancel) {

            }
        });
    });
});