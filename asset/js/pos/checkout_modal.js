$(document).ready(function () {
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
            $firstNameInput.val('POS');
            $firstNameInput.closest(".mb-3").show();
            $lastNameInput.closest(".mb-3").show();
            $lastNameInput.val('Customer');
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
    $transactionTypeSelect.on("change", function () {
        toggleFields();
    });
});

$(document).ready(function () {
    // Initialize Select2 for barangay selection
    $('#brgy').select2({
        dropdownParent: $('#address-form'),
        width: '100%',
        placeholder: 'Select your Barangay'
    });

    // Handle change in barangay selection
    $('#brgy').change(function () {
        var brgy = $(this).val();

        // AJAX call to fetch municipality based on selected barangay
        $.ajax({
            url: '/get-municipality',
            method: 'POST',
            data: {
                brgy: brgy
            },
            dataType: 'json',
            success: function (feedback) {
                console.log('Successfully fetched municipality');
                if (feedback.municipality) {
                    $('#municipality').val(feedback.municipality);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching municipality:', status, error);
            }
        });

        // AJAX call to fetch delivery fee based on selected barangay
        $.ajax({
            url: '/delivery-fee',
            method: 'POST',
            data: {
                brgy: brgy
            },
            dataType: 'json',
            success: function (feedback) {
                console.log('Successfully fetched delivery fee');
                if (feedback.delivery_fee) {
                    var deliveryFee = parseFloat(feedback.delivery_fee);
                    $('#delivery-fee-value').text('₱' + deliveryFee.toFixed(2));
                    $('#delivery-fee-value').data('fee', deliveryFee);

                    // Update the total including delivery fee
                    updateOriginalTotal();
                    calculateDiscount(); // Recalculate discount after delivery fee change
                    calculateChange(); // Recalculate change after delivery fee change
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching delivery fee:', status, error);
            }
        });
    });

    // Function to update the original total price before discount
    function updateOriginalTotal() {
        let total = 0;
        $('#cart-body-modal tr').each(function () {
            var itemTotal = parseFloat($(this).find('td').eq(5).text().replace('₱', '').replace(/,/g, '')) || 0;
            total += itemTotal;
        });

        // Add delivery fee to the total
        var deliveryFee = $('#delivery-fee-value').data('fee') || 0;
        total += deliveryFee;

        $('#cart-total-modal').text(`₱${total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`);
    }

    // Function to recalculate discount after applying changes
    function calculateDiscount() {
        var originalTotal = parseFloat($('#cart-total-modal').text().replace('₱', '').replace(/,/g, '')) || 0;
        var discount = parseFloat($('#discount-input').val()) || 0;
        var finalTotal = originalTotal - discount;
        $('#cart-total-modal').text(`₱${finalTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`);
    }

    // Function to recalculate change after applying changes
    function calculateChange() {
        var finalTotal = parseFloat($('#cart-total-modal').text().replace('₱', '').replace(/,/g, '')) || 0;
        var cashReceived = parseFloat($('#cashRec-input').val()) || 0;
        var change = cashReceived - finalTotal;
        if (change < 0) {
            change = 0;
        }
        $('#change-display').text(`₱${change.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`);
        toggleCheckoutButton(change);
    }

    // Function to toggle checkout button based on change amount
    function toggleCheckoutButton(change) {
        if (change >= 0) {
            $('.print').prop('disabled', false);
        } else {
            $('.print').prop('disabled', true);
        }
    }

    // Event listener for discount input
    $('#discount-input').on('input', function () {
        updateOriginalTotal();
        calculateDiscount(); // Recalculate total after applying discount
    });

    // Event listener for cash received input
    $('#cashRec-input').on('input', calculateChange);

    // Initial update for original total and change
    updateOriginalTotal();
    calculateDiscount(); // Ensure discount calculation on load
    calculateChange(); // Ensure change calculation on load
});
