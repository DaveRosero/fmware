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

    function generateBarcode() {
        var min = 100000000000; // Minimum 12-digit number
        var max = 999999999999; // Maximum 12-digit number
        var randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;
        return 'FM' + randomNumber.toString();
    }

    $('#product-table').DataTable({
        order: [
            [8, 'asc']
        ]
    });

    $('#category').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a category <span class="text-danger">*</span>',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $('#brand').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a brand <span class="text-danger">*</span>',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $('#unit').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a unit <span class="text-danger">*</span>',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $('#variant').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select a variant of the product <span class="text-danger">*</span>',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $('#supplier').select2({
        dropdownParent: $('#newProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select a supplier <span class="text-danger">*</span>',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
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
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
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

    $('#generate_barcode').change(function(){
        if ($(this).is(':checked')) {
            var barcode = generateBarcode();
            $('#barcode').val(barcode);
            $('#barcode').prop('readonly', true);
        } else {
            $('#barcode').val('');
            $('#barcode').prop('readonly', false);
        }
    });

    $('#non-stockable').change(function(){
        if ($(this).is(':checked')) {
            $('#stockable').val(0);
            $('#initial_stock').val(0);
            $('#critical_level').val(0);
            $('#initial_stock').prop('readonly', true);
            $('#critical_level').prop('readonly', true);
        } else {
            $('#stockable').val(1);
            $('#initial_stock').val('');
            $('#critical_level').val('');
            $('#initial_stock').prop('readonly', false);
            $('#critical_level').prop('readonly', false);
        }
    });
});