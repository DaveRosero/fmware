$(document).ready(function () {
    var logo = new Image();
    var logosrc = "asset/images/store/logo.png";
    logo.src = logosrc;

    var $transactionTypeSelect = $(".transaction-form select");
    var $firstNameInput = $("#fName-input");
    var $lastNameInput = $("#lName-input");
    var $streetInput = $("#street-input");
    var $brgyInput = $("#brgy");
    var $municipalInput = $("#municipality");
    var $contactInput = $("#contact-input");
    var $deliveryFeeContainer = $("#delivery-fee");
    var $deliveryFeeValue = $("#delivery-fee-value");
    var $delivererSelect = $("#deliverer");

    // Function to generate a unique sales receipt number
    function generateRef() {
        const bytes = new Uint8Array(10);
        window.crypto.getRandomValues(bytes);
        let hex = '';
        bytes.forEach(byte => {
            hex += byte.toString(16).padStart(2, '0');
        });
        const prefix = 'POS_';
        return prefix + hex.toUpperCase();
    }

    // Function to show/hide fields based on dropdown selection
    function toggleFields() {
        if ($transactionTypeSelect.val() === "0") { // POS selected
            $firstNameInput.val('POS');
            $firstNameInput.closest(".mb-3").show();
            $lastNameInput.val('Customer');
            $lastNameInput.closest(".mb-3").show();
            $streetInput.closest(".mb-3").hide();
            $brgyInput.closest(".mb-3").hide();
            $municipalInput.closest(".mb-3").hide();
            $contactInput.closest(".mb-3").hide();
            $deliveryFeeContainer.hide(); // Hide delivery fee
            $delivererSelect.closest(".mb-3").hide();
        } else if ($transactionTypeSelect.val() === "1") { // Walk-in selected
            $firstNameInput.closest(".mb-3").show();
            $lastNameInput.closest(".mb-3").show();
            $streetInput.closest(".mb-3").show();
            $brgyInput.closest(".mb-3").show();
            $municipalInput.closest(".mb-3").show();
            $contactInput.closest(".mb-3").show();
            $deliveryFeeContainer.show(); // Show delivery fee
            $delivererSelect.closest(".mb-3").show();
        }
        updateOriginalTotal(); // Recalculate total
        calculateDiscount(); // Recalculate discount
        calculateChange(); // Recalculate change
    }

    // Initial setup: Set dropdown to default value and toggle fields
    $transactionTypeSelect.val("0"); // Set default selection to "POS"
    toggleFields();

    // Event listener for dropdown change
    $transactionTypeSelect.on("change", function () {
        toggleFields();
    });

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
                    $deliveryFeeValue.text('₱' + deliveryFee.toFixed(2));
                    $deliveryFeeValue.data('fee', deliveryFee);

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

        if ($transactionTypeSelect.val() === "1") { // Only add delivery fee if Walk-in selected
            var deliveryFee = $deliveryFeeValue.data('fee') || 0;
            total += deliveryFee;
        }

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
        calculateDiscount(); 
    });

    // Event listener for cash received input
    $('#cashRec-input').on('input', calculateChange);

    // Initial update for original total and change
    updateOriginalTotal();
    calculateDiscount(); 
    calculateChange(); 

    // Define a function to generate printable receipt content
    function generatePrintableContent() {
        var content = "";
        var originalTotal = 0;
        var discount = parseFloat($("#discount-input").val()) || 0;
        var cashReceived = parseFloat($("#cashRec-input").val()) || 0;
        var change = $("#change-display").text();
        var deliveryFee = ($transactionTypeSelect.val() === "1") ? parseFloat($("#delivery-fee-value").data('fee')) || 0 : 0;
        var salesReceiptNumber = generateRef();
        var customerName = $("#fName-input").val() + " " + $("#lName-input").val();
        var address = $("#street-input").val() + ", " + $("#brgy option:selected").text() + ", " + $("#municipality").val();
        var contact = $("#contact-input").val();
        var delivererName = $("#deliverer option:selected").text(); // Get selected deliverer name
        var purchasedDate = new Date().toLocaleDateString(); // Get the current date

        $("#cart-body-modal tr").each(function () {
            var name = $(this).find("td:eq(0)").text();
            var variant = $(this).find("td:eq(1)").text();
            var unit = $(this).find("td:eq(2)").text();
            var qty = parseFloat($(this).find("td:eq(4)").text());
            var price = parseFloat($(this).find("td:eq(3)").text().replace('₱', '').replace(/,/g, ''));
            var total = price * qty;

            originalTotal += total;

            content +=
                '<tr><td class="center">' +
                name +
                '</td><td class="center">' +
                variant +
                '</td><td class="center">' +
                unit +
                '</td><td class="center">' +
                '₱' + price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
                '</td><td class="center">' +
                qty +
                '</td><td class="center">' +
                '₱' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
                "</td></tr>";
        });

        var finalTotal = originalTotal + deliveryFee - discount;

        var printableContent =
            "<style>" +
            "table { width: 100%; border-collapse: collapse; }" +
            "th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }" +
            "th { background-color: #f2f2f2; }" +
            ".receipt { margin: 20px auto; max-width: 400px; page-break-after: always; }" +
            ".total { font-weight: bold; text-align: right; margin-top: 10px; }" +
            ".header { text-align: center; margin-bottom: 20px; }" +
            ".logo { width: 100px; height: 100px; margin-bottom: 10px; }" +
            ".center { text-align: center; }" +
            ".signature { margin-top: 30px; }" +
            ".signature p { text-align: center; margin-top: 50px; }" +
            "</style>" +
            '<div class="receipt">' +
            '<div class="header">' +
            '<img src="' +
            logosrc +
            '" alt="Company Logo" class="logo">' +
            "<h1>F.M. ODULIOS ENTERPRISES AND GEN. MERCHANDISE</h1>" +
            "<p>Mc Arthur HI-way, Poblacion II, Marilao, Bulacan</p>" +
            "</div>" +
            "<p>Sales Receipt Number: " +
            salesReceiptNumber +
            "</p>" +
            "<p>Customer Name: " +
            customerName +
            "</p>" +
            ($transactionTypeSelect.val() === "1" ? "<p>Address: " + address + "</p>" : "") +
            ($transactionTypeSelect.val() === "1" ? "<p>Contact: " + contact + "</p>" : "") +
            ($transactionTypeSelect.val() === "1" ? "<p>Deliverer: " + delivererName + "</p>" : "") +
            "<p>Date of Purchase: " +
            purchasedDate +
            "</p>" +
            "<table>" +
            "<thead><tr><th>Item</th><th>Variant</th><th>Unit</th><th>Price</th><th>Quantity</th><th>Total</th></tr></thead>" +
            "<tbody>" +
            content +
            "</tbody>" +
            "</table>" +
            '<div class="total">' +
            "<p>Subtotal: ₱" + originalTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</p>" +
            ($transactionTypeSelect.val() === "1" ? "<p>Delivery Fee: ₱" + deliveryFee.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</p>" : "") +
            "<p>Discount: ₱" + discount.toFixed(2).replace(/\B(?=(\d{3})+(?!d))/g, ",") + "</p>" +
            "<p>Total: ₱" + finalTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</p>" +
            "<p>Cash: ₱" + cashReceived.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</p>" +
            "<p>Change: " + change + "</p>" +
            "</div>" +
            "</div>";
        return printableContent;
    }

    // Printing Functionality
    $(".print").on("click", function () {
        function printReceiptWithLogo() {
            var printableContent = generatePrintableContent();

            var printWindow = window.open("", "_blank");
            printWindow.document.write(
                "<html><head><title>Receipt</title></head><body>" +
                printableContent +
                "</body></html>"
            );
            printWindow.document.close();

            printWindow.print();

            printWindow.onafterprint = function () {
                printWindow.close();
                submitPosTransaction();
            };
        }

        if (logo.complete) {
            // If logo is already loaded, print receipt immediately
            printReceiptWithLogo();
        } else {
            // If logo is not yet loaded, wait for it to load before printing receipt
            logo.onload = printReceiptWithLogo;
        }
    });

    function submitPosTransaction() {
        $.ajax({
            url: '/pos-checkout', 
            method: 'POST',
            data: {
                user_id: $("#user_id").val(), 
                delivery_fee_value: parseFloat($("#delivery-fee-value").data('fee')).toFixed(2), 
                fname: $("#fName-input").val(),
                lname: $("#lName-input").val(),
                contact: $("#contact-input").val(),
                subtotal: parseFloat($("#cart-total-modal").text().replace('₱', '').replace(/,/g, '')).toFixed(2), 
                total: parseFloat($("#cart-total-modal").text().replace('₱', '').replace(/,/g, '')).toFixed(2), 
                discount: parseFloat($("#discount-input").val()).toFixed(2), 
                cash: parseFloat($("#cashRec-input").val()).toFixed(2), 
                changes: parseFloat($("#change-display").text().replace('₱', '').replace(/,/g, '')).toFixed(2), 
                deliverer_name: $("#deliverer option:selected").text(), 
                payment_type: $("#payment_type").val(), 
                address_id: $("#brgy").val(), 
                pos_ref: generateRef()
            },
            dataType: 'json',
            success: function (response) {
                console.log('POS transaction submitted successfully:', response);
                // Handle success response (if any)
            },
            error: function (xhr, status, error) {
                console.error('Error submitting POS transaction:', status, error);
                // Handle error condition
            }
        });
    }
    
});



