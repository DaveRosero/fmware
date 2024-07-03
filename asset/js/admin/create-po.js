$(document).ready(function(){
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
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    });
});