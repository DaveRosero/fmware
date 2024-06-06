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

    $('#pay-employees').click(function(){
        $('#pay-employees-modal').modal('show');
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

    $('#selectAll').click(function() {
        $('input[name="staff[]"]').prop('checked', this.checked);
    });

    $('input[name="staff[]"]').click(function() {
        if (!this.checked) {
            $('#selectAll').prop('checked', false);
        } else {
            if ($('input[name="staff[]"]:checked').length == $('input[name="staff[]"]').length) {
                $('#selectAll').prop('checked', true);
            }
        }
    });

    $('#pay-employees-form').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/pay-employees',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json) {
                if (json.redirect) {
                    window.location.href = json.redirect
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    });
})