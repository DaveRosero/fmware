$(document).ready(function(){
    var stock;
    var critical_level;

    function convertDateFormat(dateString) {
        if (dateString === null || dateString === undefined) {
            return null;
        }

        // Split the date string into components
        const parts = dateString.split('-');
        
        // Ensure we have year, month, and day parts
        if (parts.length !== 3) {
            return null;
        }
        
        const year = parts[0];
        const month = parts[1];
        const day = parts[2];

        // Convert the month number to a month name
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        // Validate month number
        if (isNaN(parseInt(month)) || parseInt(month) < 1 || parseInt(month) > 12) {
            return null;
        }

        // Convert the month number to a month name
        const monthName = monthNames[parseInt(month) - 1];

        // Validate day number
        if (isNaN(parseInt(day)) || parseInt(day) < 1 || parseInt(day) > 31) {
            return null;
        }

        // Create the new format Month DD, YYYY
        const formattedDate = `${monthName} ${parseInt(day)}, ${year}`;

        return formattedDate;
    }

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
                stock = json.stock;
                critical_level = json.critical_level;

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

                if (json.stockable == 1) {
                    $('#edit_non_stockable_checkbox').prop('checked', false);
                    $('#edit_stockable').val(json.stockable);
                } else {
                    $('#edit_non_stockable_checkbox').prop('checked', true);
                    $('#edit_stockable').val(json.stockable);
                    $('#edit_stock').prop('readonly', true);
                    $('#edit_critical_level').prop('readonly', true);
                }
                
                if (json.pickup == 1) {
                    $('#edit_pickup_checkbox').prop('checked', true);
                    $('#edit_pickup').val(json.pickup);
                } else {
                    $('#edit_pickup_checkbox').prop('checked', false);
                    $('#edit_pickup').val(json.pickup);
                }

                if (json.delivery == 1) {
                    $('#edit_delivery_checkbox').prop('checked', true);
                    $('#edit_delivery').val(json.delivery);
                } else {
                    $('#edit_delivery_checkbox').prop('checked', false);
                    $('#edit_delivery').val(json.delivery);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    }

    function viewProductInfo (id) {
        $.ajax({
            url: '/view-product',
            method: 'POST',
            data: {
                id : id
            },
            dataType: 'json',
            success: function(json) {
                var image = '/asset/images/products/' + json.image;
                var expiration_date = convertDateFormat(json.expiration_date);

                $('#view_image').attr('src', image);
                $('#view_name').text(json.name);
                $('#view_code').text(json.code);
                $('#view_supplier').text(json.supplier);
                $('#view_description').text(json.description);
                $('#view_expiration_date').text(expiration_date);
                $('#view_category').text(json.category);
                $('#view_brand').text(json.brand);
                $('#view_unit').text(json.unit);
                $('#view_variant').text(json.variant);
                $('#view_base_price').text(json.base_price);
                $('#view_selling_price').text(json.selling_price);
                $('#view_stock').text(json.stock);
                $('#view_critical_level').text(json.critical_level);
                $('#view_barcode').text(json.barcode);
                $('#view_pickup').html(json.pickup);
                $('#view_delivery').html(json.delivery);
                $('#view-edit-button').data('product-id', json.id);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
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

    $('.view').click(function(){
        var id = $(this).data('product-id');
        viewProductInfo(id);
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
        var formData = new FormData(this);

        $.ajax({
            url: '/edit-product',
            method: 'POST',
            data: formData,
            processData: false,  // Prevent jQuery from processing the data
            contentType: false,
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

    $('#edit_non_stockable_checkbox').change(function(){
        if ($(this).is(':checked')) {
            $('#edit_stock').val(0);
            $('#edit_critical_level').val(0);
            $('#edit_stock').prop('readonly', true);
            $('#edit_critical_level').prop('readonly', true);
            $('#edit_stockable').val(0);
        } else {
            $('#edit_stock').val(stock);
            $('#edit_critical_level').val(stock);
            $('#edit_stock').prop('readonly', false);
            $('#edit_critical_level').prop('readonly', false);
            $('#edit_stockable').val(1);
        }
    });

    $('#edit_pickup_checkbox').change(function(){
        if ($(this).is(':checked')) {
            $('#edit_pickup').val(1);
        } else {
            $('#edit_pickup').val(0);
        }
    });

    $('#edit_delivery_checkbox').change(function(){
        if ($(this).is(':checked')) {
            $('#edit_delivery').val(1);
        } else {
            $('#edit_delivery').val(0);
        }
    });
});