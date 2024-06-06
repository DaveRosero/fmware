$(document).ready(function(){
    $('#suppliers-table').DataTable({
        order: [1, 'asc']
    });

    $('#add-supplier-form').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/add-supplier',
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
})