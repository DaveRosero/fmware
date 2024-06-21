$(document).ready(function() {
    var $transactionTypeSelect = $(".transaction-form select");
    var $firstNameInput = $("#fName-input");
    var $lastNameInput = $("#lName-input");
    var $streetInput = $("#street-input");
    var $brgyInput = $("#brgy");
    var $municipalInput = $("#municipality");
    var $contactInput = $("#contact-input");

    // Function to show/hide fields based on dropdown selection
    function toggleFields() {
        if ($transactionTypeSelect.val() === "0") { // POS selected
            $firstNameInput.closest(".mb-3").show(); 
            $lastNameInput.closest(".mb-3").show(); 
            $streetInput.closest(".mb-3").hide(); 
            $brgyInput.closest(".mb-3").hide(); 
            $municipalInput.closest(".mb-3").hide(); 
            $contactInput.closest(".mb-3").hide(); 
        } else if ($transactionTypeSelect.val() === "1") { // Walk-in selected
            $firstNameInput.closest(".mb-3").show(); 
            $lastNameInput.closest(".mb-3").show(); 
            $streetInput.closest(".mb-3").show(); 
            $brgyInput.closest(".mb-3").show(); 
            $municipalInput.closest(".mb-3").show(); 
            $contactInput.closest(".mb-3").show(); 
        }
    }

    // Initial setup: Set dropdown to default value and toggle fields
    $transactionTypeSelect.val("0"); // Set default selection to "POS"
    toggleFields();

    // Event listener for dropdown change
    $transactionTypeSelect.on("change", function() {
        toggleFields();
    });
});


$(document).ready(function(){
    function getCartTotal () {
        var id = $('#checkout').data('user-id');
        var delivery_fee = $('#delivery-fee-value').val();
        $.ajax({
            url: '/cart-total',
            method: 'POST',
            data: {
                id : id,
                delivery_fee, delivery_fee
            },
            dataType: 'json',
            success: function(feedback){
                console.log(feedback);
                if (feedback.product_total) {
                    $('#product-total').text(feedback.product_total);
                }

                if (feedback.checkout_total) {
                    $('#checkout-total').text(feedback.checkout_total);
                }
            }
        });
    }

    getCartTotal();



$('#brgy').select2({
    dropdownParent: $('#address-form'),
    width: '100%',
    placeholder: 'Select your Baranggay'
});

$('#brgy').change(function(){
    var brgy = $(this).val();
    console.log('changed');

    $.ajax({
        url: '/get-municipality',
        method: 'POST',
        data: {
            brgy : brgy
        },
        dataType: 'json',
        success: function(feedback){
            console.log('success');
            console.log(feedback.municipality);
            if (feedback.municipality) {
                $('#municipality').val(feedback.municipality);
            }
        }
    });
});

$('#address').change(function(){
    var brgy = $(this).val();
    var selected = $(this).find('option:selected');
    var address_id = selected.data('address-id');
    $.ajax({
        url: '/delivery-fee',
        method: 'POST',
        data: {
            brgy : brgy
        },
        dataType: 'json',
        success: function(feedback){
            if (feedback) {
                $('#delivery-fee').text('₱' + feedback.delivery_fee + '.00');
                $('#delivery-fee-value').val(feedback.delivery_value);
                $('#address_id').val(address_id);
                getCartTotal();
            }
        }
    });
})
})
