$(document).ready(function(){
    function updateProductStatus (active, id) {
        $.ajax({
            url: '/disable-product',
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
                $('#discount').val(json.cart_total);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    }
    $('#product-table').DataTable({
        order: [
            [2, 'asc']
        ]
    });

    $('#category').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a category',
        theme: 'bootstrap-5'
    });

    $('#brand').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a brand',
        theme: 'bootstrap-5'
    });

    $('#unit').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a measurement',
        theme: 'bootstrap-5'
    });

    $('#variant').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select a variant of the product',
        theme: 'bootstrap-5'
    });

    $('#new-product').on('submit', function(event){
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '/new-product',
            method: 'POST',
            data: formData,
            processData: false,  // Prevent jQuery from processing the data
            contentType: false,
            dataType: 'json',
            success: function(feedback){
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }

                if (feedback.exist) {
                    $.notify('Product already exist', 'error');
                }
            }
        });
    });

    $('.status').change(function(){
        if ($(this).is(':checked')) {
            var id = $(this).data('product-id');
            var active = 1;
            updateProductStatus(active, id);
        } else {
            var id = $(this).data('product-id');
            var active = 0;
            updateProductStatus(active, id);
        }
    })

    $('.edit').on('click', function(){
        $('#edit-label').html('Editing <strong>"' + $(this).data('product-name') + '</strong>"');
        $('#product_name').val($(this).data('product-name'));
        $('#product_id').val($(this).data('product-id'));
        $('#editProduct').modal('show');
    });

    $('#edit-product').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/edit-product',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                $('#edit_feedback').text(feedback.edit_feedback);
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
            }
        });
    });
});