$(document).ready(function(){
    $('#expenses-table').DataTable({
        order: [
            [2, 'desc']
        ]
    });
    $('#wage-table').DataTable();

    $('#add-expenses').click(function(){
        $('#expenses-modal').modal('show');
    });

    $('#add-expenses-form').on('submit', function(event){
        event.preventDefault();
        console.log('form submiteed');

        $.ajax({
            url: '/add-expenses',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json) {
                console.log(json);
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