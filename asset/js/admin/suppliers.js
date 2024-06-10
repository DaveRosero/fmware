$(document).ready(function(){
    function updateSupplierStatus (active, id) {
        $.ajax({
            url: '/update-supplier',
            method: 'POST',
            data: {
                id : id,
                active : active
            },
            dataType: 'json',
            success: function(json){
                if (json.redirect) {
                    window.location.href = json.redirect;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    }
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

    $('.edit').on('click', function(){
        var id = $(this).data('supplier-id');

        $.ajax({
            url: '/get-supplier',
            method: 'POST',
            data: {
                id : id
            },
            dataType: 'json',
            success: function(json){
                $('#edit-id').val(json.id);
                $('#edit-supplier').val(json.supplier);
                $('#edit-email').val(json.email);
                $('#edit-phone').val(json.phone);
                $('#edit-address').val(json.address);
                $('#edit-contact').val(json.contact);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    });

    $('#edit-supplier-form').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/edit-supplier',
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
            var id = $(this).data('supplier-id');
            var active = 1;
            updateSupplierStatus(active, id);
        } else {
            var id = $(this).data('supplier-id');
            var active = 0;
            updateSupplierStatus(active, id);
        }
    })
})