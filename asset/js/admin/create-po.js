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
});