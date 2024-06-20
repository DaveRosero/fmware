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
        var min = 100000000000;
        var max = 999999999999;
        var randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;
        return 'FM' + randomNumber.toString();
    }

    function getProductInfo (id) {
        $.ajax({
            url:'/get-product',
            method: 'POST',
            data: {
                id : id
            },
            dataType: 'json',
            success: function(json) {
                $('#edit_name').val(json.name);
                $('#edit_code').val(json.code);
                $('#edit_supplier').val(json.supplier).change();
                $('#edit_description').val(json.description);
                $('#edit_expiration_date').val(json.expiration);
                $('#edit_brand').val(json.brand).change();
                $('#edit_unit_value').val(json.unit_value);
                $('#edit_unit').val(json.unit).change();
                $('#edit_category').val(json.category).change();
                $('#edit_variant').val(json.variant).change();
                $('#edit_base_price').val(json.base_price);
                $('#edit_selling_price').val(json.selling_price);
                $('#edit_stock').val(json.stock);
                $('#edit_critical_level').val(json.critical_level);
                $('#edit_barcode').val(json.barcode);
                $('#edit_id').val(json.id);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    }

    $('#product-table').DataTable({
        order: [
            [7, 'asc']
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

    $('#edit_category').select2({
        dropdownParent: $('#editProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a category <span class="text-danger">*</span>',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $('#edit_brand').select2({
        dropdownParent: $('#editProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a brand <span class="text-danger">*</span>',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $('#edit_unit').select2({
        dropdownParent: $('#editProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select or type a unit <span class="text-danger">*</span>',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $('#edit_variant').select2({
        dropdownParent: $('#editProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select a variant of the product <span class="text-danger">*</span>',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $('#edit_supplier').select2({
        dropdownParent: $('#editProduct'),
        tags: true,
        width: '100%',
        placeholder: 'Select a supplier <span class="text-danger">*</span>',
        theme: 'bootstrap-5',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    $('.edit').click(function(){
        var id = $(this).data('product-id');
        getProductInfo(id);
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

    $('#pickup_checkbox').change(function(){
        if ($(this).is(':checked')) {
            $('#pickup').val(1);
        } else {
            $('#pickup').val(0);
        }
    });

    $('#delivery_checkbox').change(function(){
        if ($(this).is(':checked')) {
            $('#delivery').val(1);
        } else {
            $('#delivery').val(0);
        }
    });

    $('#edit-product').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/edit-product',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json) {
                if (json.redirect) {
                    window.location.href =  json.redirect;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    });
});