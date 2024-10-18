$(document).ready(function () {
  // Handle POS Payment button click
  $("#posPaymentButton").on("click", function () {
    const posRef = $(this).data("pos-ref");

    // Hide the acceptedPOS-items-modal
    $("#acceptedPOS-items-modal").modal("hide");

    // Fetch POS details and show the pos-payment-modal
    fetchPOSData(posRef);
  });

  // Format currency for display
  function formatCurrency(amount) {
    return (
      "₱" +
      parseFloat(amount || 0)
        .toFixed(2)
        .replace(/\d(?=(\d{3})+\.)/g, "$&,")
    );
  }

  // Fetch POS details and populate the modal
  function fetchPOSData(posRef) {
    $.ajax({
      url: "/model-pos-details",
      method: "GET",
      data: { pos_ref: posRef },
      success: function (response) {
        if (response) {
          const orderPrice = response.subtotal - response.delivery_fee ?? 0;
          const discount = response.discount ?? 0;
          const deliveryFee = response.delivery_fee ?? 0;
          const totalPrice = orderPrice - discount + deliveryFee;

          $("#pos-ref").text(`POS Ref: ${response.pos_ref}`);
          $("#pos-price").text(`Pos Price: ${formatCurrency(orderPrice)}`);
          $("#pos-discount").text(`Discount: ${formatCurrency(discount)}`);
          $("#pos-delivery-fee").text(
            `Delivery Fee: ${formatCurrency(deliveryFee)}`
          );
          $("#pos-total").text(`Total Price: ${formatCurrency(totalPrice)}`);

          // Check if the POS is already paid
          if (response.paid === "paid") {
            $('input[name="paymentMethod"]').prop("disabled", true);
            $("#cash-received").prop("disabled", true);
            $("#delivered-btn").prop("disabled", false);
          } else {
            // Enable input field for cash received
            $('input[name="paymentMethod"]').prop("disabled", false);
            $("#cash-received").prop("disabled", false);
            $("#delivered-btn").prop("disabled", true);
          }
        } else {
          // Handle no data response
          $("#pos-ref").text("POS Ref: N/A");
          $("#pos-price").text("Order Price: ₱0.00");
          $("#pos-discount").text("Discount: ₱0.00");
          $("#pos-delivery-fee").text("Delivery Fee: ₱0.00");
          $("#pos-total").text("Total Price: ₱0.00");
          $("#cash-received").prop("disabled", false);
          $("#delivered-btn").prop("disabled", true);
        }
      },
      error: function () {
        console.error("Failed to fetch POS data");
        $("#pos-ref").text("POS Ref: N/A");
        $("#pos-price").text("Order Price: ₱0.00");
        $("#pos-discount").text("Discount: ₱0.00");
        $("#pos-delivery-fee").text("Delivery Fee: ₱0.00");
        $("#pos-total").text("Total Price: ₱0.00");
        $("#cash-received").prop("disabled", false);
        $("#delivered-btn").prop("disabled", true);
      },
    });
  }

  // Update change amount based on cash received
  function updateChange() {
    const totalPrice =
      parseFloat(
        $("#pos-total").text().replace("Total Price: ₱", "").replace(",", "")
      ) || 0;
    const cashReceived = parseFloat($("#cash-received").val()) || 0;
    const change = cashReceived - totalPrice;

    if (cashReceived >= totalPrice) {
      $("#change").text(`Change: ${formatCurrency(change)}`);
      $("#delivered-btn").prop("disabled", false);
    } else {
      $("#change").text("Change: ₱0.00");
      $("#delivered-btn").prop("disabled", true);
    }
  }

  // Handle payment process
  function processPayment() {
    const paymentMethod = $('input[name="paymentMethod"]:checked').val();
    const posRef = $("#pos-ref").text().replace("POS Ref: ", "");
    const cashReceived = parseFloat($("#cash-received").val()) || 0;

    if (!posRef || isNaN(cashReceived)) {
      Swal.fire({
        icon: "warning",
        title: "Missing Information",
        text: "Please ensure all information is provided.",
      });
      return;
    }

    $.ajax({
      url: "/model-processPosPayment",
      method: "POST",
      data: {
        pos_ref: posRef,
        status: "delivered",
        payment_type: paymentMethod,
        cash_received: cashReceived,
      },
      success: function (response) {
        if (response.success) {
          Swal.fire({
            icon: "success",
            title: "Payment Processed",
            text: "Payment processed successfully.",
          }).then(() => {
            // Close the modal

            $("#pos-payment-modal").modal("hide");
            // Optionally, reload or update relevant parts of the page
            window.location.href = "/rider-history";
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Payment Failed",
            text: "Failed to process payment. Please try again.",
          });
        }
      },
      error: function () {
        console.error("Failed to process payment");
        Swal.fire({
          icon: "error",
          title: "Payment Failed",
          text: "Failed to process payment. Please try again.",
        });
      },
    });
  }

  // Event handlers
  $("#cash-received").on("input", updateChange);

  $("#delivered-btn").on("click", processPayment);

  $("#posPaymentButton").on("click", function () {
    const posRef = $(this).data("pos-ref");
    if (posRef) {
      fetchPOSData(posRef);
      $("#pos-payment-modal").modal("show");
    }
  });
});
