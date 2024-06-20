$(document).ready(function() {
    const $transactionTypeSelect = $(".transaction-form select");
    const $firstNameInput = $("#fName-input");
    const $lastNameInput = $("#lName-input");
    const $addressInput = $("#address-input");
    const $contactInput = $("#contact-input");

    // Function to show/hide fields based on dropdown selection
    function toggleFields() {
        if ($transactionTypeSelect.val() === "0") { // POS selected
            $firstNameInput.closest(".mb-3").show(); // Show parent div containing input
            $lastNameInput.closest(".mb-3").show(); // Show parent div containing input
            $addressInput.closest(".mb-3").hide(); // Hide parent div containing input
            $contactInput.closest(".mb-3").hide(); // Hide parent div containing input
        } else if ($transactionTypeSelect.val() === "1") { // Walk-in selected
            $firstNameInput.closest(".mb-3").show(); // Show parent div containing input
            $lastNameInput.closest(".mb-3").show(); // Show parent div containing input
            $addressInput.closest(".mb-3").show(); // Show parent div containing input
            $contactInput.closest(".mb-3").show(); // Show parent div containing input
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
