$(document).ready(function(){
    $('#purchase-order-table').DataTable();

    $('#supplier').select2({
        dropdownParent: $('#createPO'),
        width: '100%',
        placeholder: 'Select a supplier',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $('#redirect').submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '/redirect-po',
            method: 'POST',
            data: $(this).serialize(),
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
        });
    });
});