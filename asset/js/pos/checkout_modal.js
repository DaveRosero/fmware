$(document).ready(function () {
  var logo = new Image();
  var logosrc = "asset/images/store/logo.png";
  logo.src = logosrc;

  var $transactionTypeSelect = $(".transaction-form select");
  var $paymentTypeSelect = $(".transaction-form select[name='payment_type']");
  var $brgySelect = $(".brgy select[name='address']");
  var $firstNameInput = $("#fName-input");
  var $lastNameInput = $("#lName-input");
  var $streetInput = $("#street-input");
  var $brgyInput = $("#brgy");
  var $municipalInput = $("#municipality");
  var $contactInput = $("#contact-input");
  var $deliveryFeeContainer = $("#delivery-fee");
  var $deliveryFeeValue = $("#delivery-fee-value");
  var $delivererSelect = $("#deliverer");

  // Function to show/hide fields based on dropdown selection
  function toggleFields() {
    if ($transactionTypeSelect.val() === "0") {
      // Walk-in selected
      $firstNameInput.val("POS");
      $firstNameInput.closest(".mb-3").show();
      $lastNameInput.val("Customer");
      $lastNameInput.closest(".mb-3").show();
      $streetInput.closest(".mb-3").hide();
      $brgyInput.closest(".mb-3").hide();
      $municipalInput.closest(".mb-3").hide();
      $contactInput.closest(".mb-3").hide();
      $deliveryFeeContainer.hide(); // Hide delivery fee
      $deliveryFeeValue.hide();
      $delivererSelect.closest(".mb-3").hide();
    } else if ($transactionTypeSelect.val() === "1") {
      // delivery selected
      $firstNameInput.closest(".mb-3").show();
      $lastNameInput.closest(".mb-3").show();
      $streetInput.closest(".mb-3").show();
      $brgyInput.closest(".mb-3").show();
      $municipalInput.closest(".mb-3").show();
      $contactInput.closest(".mb-3").show();
      $deliveryFeeContainer.show(); // Show delivery fee
      $deliveryFeeValue.show();
      $delivererSelect.closest(".mb-3").show();
    }
    updateOriginalTotal(); // Recalculate total
    calculateDiscount(); // Recalculate discount
    calculateChange(); // Recalculate change
  }

  // Initial setup: Set dropdown to default value and toggle fields
  $transactionTypeSelect.val("0"); // Set default selection to "POS"
  $paymentTypeSelect.val("3"); // Set default selection to "Cash"
  $brgySelect.val("0"); // Set default selection to "Select your Baranggay"
  toggleFields();

  // Event listener for dropdown change
  $transactionTypeSelect.on("change", function () {
    toggleFields();
  });

  // Handle change in barangay selection
  $("#brgy").change(function () {
    var brgy = $(this).val();

    // AJAX call to fetch municipality based on selected barangay
    $.ajax({
      url: "/get-municipality",
      method: "POST",
      data: {
        brgy: brgy,
      },
      dataType: "json",
      success: function (feedback) {
        console.log("Successfully fetched municipality");
        if (feedback.municipality) {
          $("#municipality").val(feedback.municipality);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching municipality:", status, error);
      },
    });

    // AJAX call to fetch delivery fee based on selected barangay
    $.ajax({
      url: "/delivery-fee",
      method: "POST",
      data: {
        brgy: brgy,
      },
      dataType: "json",
      success: function (feedback) {
        console.log("Successfully fetched delivery fee");
        if (feedback.delivery_fee) {
          var deliveryFee = parseFloat(feedback.delivery_fee);
          $deliveryFeeValue.text("₱" + deliveryFee.toFixed(2));
          $deliveryFeeValue.data("fee", deliveryFee);

          // Update the total including delivery fee
          updateOriginalTotal();
          calculateDiscount(); // Recalculate discount after delivery fee change
          calculateChange(); // Recalculate change after delivery fee change
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching delivery fee:", status, error);
      },
    });
  });

  // Function to update the original total price before discount
  function updateOriginalTotal() {
    let total = 0;
    $("#cart-body-modal tr").each(function () {
      var itemTotal =
        parseFloat(
          $(this).find("td").eq(5).text().replace("₱", "").replace(/,/g, "")
        ) || 0;
      total += itemTotal;
    });

    if ($transactionTypeSelect.val() === "1") {
      // Only add delivery fee if Walk-in selected
      var deliveryFee = $deliveryFeeValue.data("fee") || 0;
      total += deliveryFee;
    }

    $("#cart-total-modal").text(
      `₱${total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
    );
  }

  // Function to recalculate discount after applying changes
  function calculateDiscount() {
    var originalTotal =
      parseFloat(
        $("#cart-total-modal").text().replace("₱", "").replace(/,/g, "")
      ) || 0;
    var discount = parseFloat($("#discount-input").val()) || 0;
    var finalTotal = originalTotal - discount;
    $("#cart-total-modal").text(
      `₱${finalTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
    );
  }

  // Function to recalculate change after applying changes
  function calculateChange() {
    var finalTotal =
      parseFloat(
        $("#cart-total-modal").text().replace("₱", "").replace(/,/g, "")
      ) || 0;
    var cashReceived = parseFloat($("#cashRec-input").val()) || 0;
    var change = cashReceived - finalTotal;
    if (change < 0) {
      change = 0;
    }
    $("#change-display").text(
      `₱${change.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
    );
    validateCheckoutButton();
  }

  // Function to validate all required fields
  function validateCheckoutButton() {
    let isValid = true;

    if ($transactionTypeSelect.val() === "1") {
      // Check required fields for Walk-in
      isValid =
        $firstNameInput.val().trim() !== "" &&
        $lastNameInput.val().trim() !== "" &&
        $streetInput.val().trim() !== "" &&
        $brgyInput.val() !== "" &&
        $contactInput.val().trim() !== "";
    }

    // Check if the cash received is greater than or equal to the total amount
    isValid =
      isValid &&
      parseFloat($("#cashRec-input").val()) >=
        parseFloat(
          $("#cart-total-modal").text().replace("₱", "").replace(/,/g, "")
        );

    $(".print").prop("disabled", !isValid);
  }

  // Event listeners for input changes to validate checkout button
  $firstNameInput.on("input", validateCheckoutButton);
  $lastNameInput.on("input", validateCheckoutButton);
  $streetInput.on("input", validateCheckoutButton);
  $brgyInput.on("change", validateCheckoutButton);
  $contactInput.on("input", validateCheckoutButton);
  $("#cashRec-input").on("input", validateCheckoutButton);

  // Event listener for discount input
  $("#discount-input").on("input", function () {
    updateOriginalTotal();
    calculateDiscount(); // Recalculate total after applying discount
  });

  // Event listener for cash received input
  $("#cashRec-input").on("input", calculateChange);
  $("#cart-total-modal").on("DOMSubtreeModified", calculateChange);

  // Initial update for original total and change
  updateOriginalTotal();
  calculateDiscount(); // Ensure discount calculation on load
  calculateChange(); // Ensure change calculation on load

  // Define a function to generate printable receipt content
  function generatePrintableContent() {
    var content = "";
    var originalTotal = 0;
    var discount = parseFloat($("#discount-input").val()) || 0;
    var cashReceived = parseFloat($("#cashRec-input").val()) || 0;
    var change = $("#change-display").text();
    var transactionType = $transactionTypeSelect.val();
    var deliveryFee =
      transactionType === "1"
        ? parseFloat($("#delivery-fee-value").data("fee")) || 0
        : 0;
    var salesReceiptNumber = generateRef();
    var customerName = `${$("#fName-input").val()} ${$("#lName-input").val()}`;
    var address = `${$("#street-input").val()}, ${$(
      "#brgy option:selected"
    ).text()}, ${$("#municipality").val()}`;
    var contact = $("#contact-input").val();
    var delivererName = $("#deliverer option:selected").text();
    var purchasedDate = new Date().toLocaleDateString();

    $("#cart-body-modal tr").each(function () {
      var name = $(this).find("td:eq(0)").text();
      var variant = $(this).find("td:eq(1)").text();
      var unit = $(this).find("td:eq(2)").text();
      var qty = parseFloat($(this).find("td:eq(4)").text());
      var price = parseFloat(
        $(this).find("td:eq(3)").text().replace("₱", "").replace(/,/g, "")
      );
      var total = price * qty;

      originalTotal += total;

      content += `
        <tr>
          <td class="center">${name}</td>
          <td class="center">${variant}</td>
          <td class="center">${unit}</td>
          <td class="center">₱${price
            .toFixed(2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
          <td class="center">${qty}</td>
          <td class="center">₱${total
            .toFixed(2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
        </tr>`;
    });

    var finalTotal = originalTotal + deliveryFee - discount;

    var printableContent = `
      <style>
        @page {
          size: 80mm 70mm;
          margin: 0;
        }
        body {
          margin: 0;
          padding: 0;
        }
        .receipt {
          width: 80mm;
          max-width: 80mm;
          margin: 0 auto;
          font-family: Arial, sans-serif;
          font-size: 10px;
        }
        .header {
          text-align: center;
          margin-bottom: 10px;
        }
        .logo {
          width: 50px;
          height: 50px;
          margin-bottom: 5px;
        }
        table {
          width: 100%;
          border-collapse: collapse;
        }
        th, td {
          padding: 4px;
          text-align: left;
          border-bottom: 1px solid #ddd;
        }
        th {
          background-color: #f2f2f2;
        }
        .total {
          font-weight: bold;
          text-align: right;
          margin-top: 5px;
        }
        .center {
          text-align: center;
        }
        .signature {
          margin-top: 15px;
        }
        .signature p {
          text-align: center;
          margin-top: 25px;
        }
      </style>
      <div class="receipt">
        <div class="header">
          <img src="${logosrc}" alt="Company Logo" class="logo">
          <h2>F.M. ODULIOS ENTERPRISES AND GEN. MERCHANDISE</h2>
          <p>Mc Arthur HI-way, Poblacion II, Marilao, Bulacan</p>
        </div>
        <p>Sales Receipt Number: ${salesReceiptNumber}</p>
        <p>Customer Name: ${customerName}</p>
        ${transactionType === "1" ? `<p>Address: ${address}</p>` : ""}
        ${transactionType === "1" ? `<p>Contact: ${contact}</p>` : ""}
        ${transactionType === "1" ? `<p>Deliverer: ${delivererName}</p>` : ""}
        <p>Date of Purchase: ${purchasedDate}</p>
        <table>
          <thead>
            <tr><th>Item</th><th>Variant</th><th>Unit</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
          </thead>
          <tbody>
            ${content}
          </tbody>
        </table>
        <div class="total">
          <p>Subtotal: ₱${originalTotal
            .toFixed(2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</p>
          ${
            transactionType === "1"
              ? `<p>Delivery Fee: ₱${deliveryFee
                  .toFixed(2)
                  .replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</p>`
              : ""
          }
          <p>Discount: ₱${discount
            .toFixed(2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</p>
          <p>Total: ₱${finalTotal
            .toFixed(2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</p>
          <p>Cash: ₱${cashReceived
            .toFixed(2)
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</p>
          <p>Change: ${change}</p>
        </div>
      </div>`;

    return printableContent;
  }

  function resetCart() {
    $.ajax({
      url: "/pos-reset",
      success: function (response) {
        // Clear cart body content
        $("#cart-body").empty();
        $("#cart-body-modal").empty();

        // Reset total displays
        $("#cart-total").text("Subtotal: ₱0.00");
        $("#cart-total-modal").text("₱0.00");
        $("#cart-subtotal-modal").text("₱0.00");

        // Log success and disable checkout button
        console.log("Cart reset successful");
        $("#checkout-button").prop("disabled", true);
      },
      error: function (xhr, status, error) {
        console.error("AJAX call failed for cart reset");
        console.error("Status:", status);
        console.error("Error:", error);
      },
    });
  }

  function resetForm() {
    $("#discount-input").val("");
    $("#cashRec-input").val("");
    $firstNameInput.val("");
    $lastNameInput.val("");
    $streetInput.val("");
    $brgyInput.val(null).trigger("change");
    $municipalInput.val("");
    $contactInput.val("");
    $delivererSelect.val("0").trigger("change");
    $deliveryFeeValue.text("₱0.00").data("fee", 0);
    $("#cart-total-modal").text("₱0.00");
    $("#change-display").text("₱0.00");
    updateOriginalTotal(); // Ensure totals are recalculated
    calculateDiscount(); // Ensure discount is recalculated
    calculateChange(); // Ensure change is recalculated
    // Refresh the page
    location.reload();
  }

  // Event listeners for close buttons to reset form
  $("#close-button").on("click", resetForm);
  $("#footer-close-button").on("click", resetForm);

  // Function to generate a unique sales receipt number
  function generateRef() {
    const bytes = new Uint8Array(10);
    window.crypto.getRandomValues(bytes);
    let hex = "";
    bytes.forEach((byte) => {
      hex += byte.toString(16).padStart(2, "0");
    });
    const prefix = "POS_";
    return prefix + hex.toUpperCase();
  }

  // Printing Functionality
  $(".print").on("click", function () {
    var pos_ref = generateRef();

    submitForm(pos_ref); // Submit the form before printing
    // Call handlePrintAndAlert after form submission
    handlePrintAndAlert();
    // Function to handle printing and Swal alert in sequence
  });

  function handlePrintAndAlert() {
    printReceipt(); // Start printing

    // Swal alert shown after printing completes
    Swal.fire({
      title: "Transaction successful!",
      icon: "success",
      showCancelButton: false,
      confirmButtonText: "Ok",
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        setTimeout(() => {
          window.location.href = "/pos";
        }, 500);
      }
    });
  }

  function submitForm(pos_ref) {
    var formData = $("#transaction-form").serializeArray();
    formData.push({ name: "pos_ref", value: pos_ref });

    function transaction() {
      var deliveryFee =
        parseFloat($("#delivery-fee-value-hidden").data("fee")) || 0;
      var total =
        parseFloat(
          $("#cart-total-modal").text().replace("₱", "").replace(/,/g, "")
        ) || 0;
      var discount = parseFloat($("#discount-input").val()) || 0;
      var subtotal = parseFloat(total + discount) || 0;
      var cash = parseFloat($("#cashRec-input").val()) || 0;
      var changes = parseFloat(cash - total) || 0;
      var deliverer = $("#deliverer-name-hidden").val() || ""; // Assuming deliverer is a select element
      var address =
        $("#street-input").val() +
        ", " +
        $("#brgy option:selected").text() +
        ", " +
        $("#municipality").val();

      formData.push({ name: "delivery-fee-value", value: deliveryFee });
      formData.push({ name: "subtotal", value: subtotal });
      formData.push({ name: "total", value: total });
      formData.push({ name: "discount", value: discount });
      formData.push({ name: "cash", value: cash });
      formData.push({ name: "changes", value: changes });
      formData.push({ name: "deliverer_name", value: deliverer });
      formData.push({ name: "address", value: address });
    }

    function transaction2() {
      var deliveryFee = parseFloat($("#delivery-fee-value").data("fee")) || 0;
      var total =
        parseFloat(
          $("#cart-total-modal").text().replace("₱", "").replace(/,/g, "")
        ) || 0;
      var discount = parseFloat($("#discount-input").val()) || 0;
      var subtotal = parseFloat(total + discount) || 0;
      var cash = parseFloat($("#cashRec-input").val()) || 0;
      var changes = parseFloat(cash - total) || 0;
      var deliverer = $("#deliverer option:selected").text() || ""; // Assuming deliverer is a select element
      var address =
        $("#street-input").val() +
        ", " +
        $("#brgy option:selected").text() +
        ", " +
        $("#municipality").val();
      var contact = $("#contact-input").val();

      formData.push({ name: "delivery-fee-value", value: deliveryFee });
      formData.push({ name: "subtotal", value: subtotal });
      formData.push({ name: "total", value: total });
      formData.push({ name: "discount", value: discount });
      formData.push({ name: "cash", value: cash });
      formData.push({ name: "changes", value: changes });
      formData.push({ name: "deliverer_name", value: deliverer });
      formData.push({ name: "address", value: address });
      formData.push({ name: "contact", value: contact });
    }

    if ($transactionTypeSelect.val() === "0") {
      transaction();
    } else {
      transaction2();
    }

    $.ajax({
      url: "/pos-checkout",
      method: "POST",
      data: $.param(formData),
      success: function (response) {
        resetCart();
        resetForm();
        printReceipt();
      },
      error: function (xhr, status, error) {
        console.error("Error in form submission:", status, error);
      },
    });
  }

  function printReceipt() {
    function printReceiptWithLogo() {
      var printableContent = generatePrintableContent();

      // Create a hidden iframe
      var iframe = document.createElement("iframe");
      iframe.style.position = "absolute";
      iframe.style.width = "0px";
      iframe.style.height = "0px";
      iframe.style.border = "none";
      document.body.appendChild(iframe);

      var doc = iframe.contentWindow.document;
      doc.open();
      doc.write(
        "<html><head><title>Receipt</title></head><body>" +
          printableContent +
          "</body></html>"
      );
      doc.close();

      iframe.contentWindow.focus();
      iframe.contentWindow.print();

      // Remove the iframe after printing
      iframe.parentNode.removeChild(iframe);
    }

    if (logo.complete) {
      // If logo is already loaded, print receipt immediately
      printReceiptWithLogo();
    } else {
      // If logo is not yet loaded, wait for it to load before printing receipt
      logo.onload = printReceiptWithLogo;
    }
  }
});
