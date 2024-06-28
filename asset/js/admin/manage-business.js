$(document).ready(function(){
    function updateDeliveryFeeStatus (active, id) {
        $.ajax({
            url: '/update-df-status',
            method: 'POST',
            data: {
                active : active,
                id : id
            },
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
        })
    }

    function delExpense (id) {
        $.ajax({
            url: '/delete-expenses',
            method: 'POST',
            data: {
                id : id
            },
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
    }

    $('#expenses-table').DataTable({
        'ordering': false
    });

    $('#municipal-table').DataTable({
        order: [
            [1, 'asc']
        ]
    });

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

    $('.edit').click(function(){
        var id = $(this).data('df-id');

        $.ajax({
            url: '/get-df',
            method: 'POST',
            data: {
                id : id
            },
            dataType: 'json',
            success: function(json) {
                $('#municipal').val(json.municipal);
                $('#df').val(json.df);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    });

    $('#edit-df-form').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/update-df',
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
        })
    });

    $('.status').change(function(){
        if ($(this).is(':checked')) {
            var id = $(this).data('df-id');
            var active = 1;
            updateDeliveryFeeStatus(active, id);
        } else {
            var id = $(this).data('df-id');
            var active = 0;
            updateDeliveryFeeStatus(active, id);
        }
    });

    $('.delete').click(function(){
        var id = $(this).data('expense-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                delExpense(id);
            } else if (result.dismiss === Swal.DismissReason.cancel) {

            }
        });
    });
})