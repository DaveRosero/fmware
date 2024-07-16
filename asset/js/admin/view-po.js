$(document).ready(function() {
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

    $('#printButton').click(function() {
        var content = $('#printContent').html();
        var originalContent = $('body').html();

        $('body').html(content);

        window.print();
        $('body').html(originalContent);
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