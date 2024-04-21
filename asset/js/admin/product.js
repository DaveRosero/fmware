$(document).ready(function(){
    $('#product-table').DataTable({
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i>',
                className: 'btn btn-secondary',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        order: [
            [2, 'asc']
        ],
        initComplete: function () {
            var dataTableButtons = $('.dt-buttons');
            $('#printButtonContainer').append(dataTableButtons);
        }
    });

    $('#category').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a category'
    });

    $('#brand').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a brand'
    });

    $('#unit').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a measurement'
    });

    $('#variant').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select a variant of the product'
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

    $('.status').on('click', function(){
        $.ajax({
            url: '/disable-product',
            method: 'POST',
            data: {
                id : $(this).data('product-id'),
                status : $(this).data('product-status')
            },
            dataType: 'json', 
            success: function(feedback){
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
            }
        });
    });

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