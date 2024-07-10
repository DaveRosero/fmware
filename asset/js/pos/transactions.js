$(document).ready(function () {

  $(".view-transaction-btn").click(function () {
    const posRef = $(this).data("bs-posref");

    $.ajax({
      url: "/pos-transactions",
      method: "GET",
      data: {
        pos_ref: posRef,
      },
      dataType: "json",
      success: function (data) {
        console.log("Transaction Data: ", data);

        // Number formatter for currency
        var formatter = new Intl.NumberFormat("en-PH", {
          style: "currency",
          currency: "PHP",
        });

        // Update general transaction details
        $("#transactionViewLabel").text("Transaction #" + data.pos_ref);
        $("#transaction-date").text(data.date);
        $("#transaction-subtotal").text(formatter.format(Number(data.subtotal)));
        $("#rtransaction-total").text(formatter.format(Number(data.total)));
        $("#rviewdiscountRec-input").val(data.discount);
        $("#rviewcashRec-input").val(Number(data.cash).toFixed(2));
        $("#rtransaction-change").text(formatter.format(Number(data.changes)));
        $("#transaction-status").text(data.status);
        $("#rtrans-username").text(data.username);

        // Clear and set payment method
        $("#rpaymentMethod").empty();
        $("#rpaymentMethod").append(new Option("G-Cash", "G-Cash"));
        $("#rpaymentMethod").append(new Option("Cash", "Cash"));
        if ($("#rpaymentMethod option[value='" + data.payment_type + "']").length === 0) {
          $("#rpaymentMethod").append($("<option>", {
            value: data.payment_type,
            text: data.payment_type,
          }));
        }
        $("#rpaymentMethod").val(data.payment_type);

        // Clear and set transaction type
        $("#rtransaction-type").empty();
        if ($("#rtransaction-type option[value='" + data.transaction_type + "']").length === 0) {
          $("#rtransaction-type").append($("<option>", {
            value: data.transaction_type,
            text: data.transaction_type,
          }));
        }
        $("#rtransaction-type").val(data.transaction_type);

        // Show or hide customer details based on transaction type
        if (data.transaction_type === "Walk-in") {
          $("#customer-details").show();
          $("#rfName-input").val(data.firstname);
          $("#rlName-input").val(data.lastname);
          $("#viewstreet-input, #street-label").val("").hide();
          $("#viewbrgy-input, #brgy-label").val("").hide();
          $("#viewmunicipality-input, #municipality-label").val("").hide();
          $("#viewcontact-input, #contact-label").val("").hide();
          $("#viewdeliverer-input, #deliverer-label").val("").hide();
        } else if (data.transaction_type === "Delivery") {
          $("#customer-details").show();
          $("#rfName-input").val(data.firstname);
          $("#rlName-input").val(data.lastname);

          let addressParts = data.address.split(", ");
          let street = addressParts[0];
          let baranggay = addressParts[1];
          let municipality = addressParts[2];

          $("#viewstreet-input, #street-label").val(street).show();
          $("#viewbrgy-input, #brgy-label").val(baranggay).show();
          $("#viewmunicipality-input, #municipality-label").val(municipality).show();
          $("#viewcontact-input, #contact-label").val(data.contact_no).show();
          $("#viewdeliverer-input, #deliverer-label").val(data.deliverer_name).show();
        }

        $("#refund-TotalValue").text("₱0.00");
        $.ajax({
          url: "/pos-transactionItems",
          method: "GET",
          data: {
            pos_ref: posRef,
          },
          dataType: "html",
          success: function (data) {
            $("#transactionItems").html(data);

            updateRefundTotal();


            $(".selectedItem, .refund-quantity").on('change keyup', function () {
              updateRefundTotal();
            });

            $(".selectedItem").change(function () {
              let inputField = $(this).closest("tr").find(".refund-quantity");
              let isChecked = $(this).is(':checked');

              // Set input value to 1 if checked, otherwise 0
              inputField.val(isChecked ? 1 : 0);

              // Enable/disable input based on checkbox state
              inputField.prop("disabled", !isChecked);

              updateRefundTotal();
            });


            $(".refund-quantity").change(function () {
              updateRefundTotal();
            });

            function updateRefundTotal() {
              let totalRefundValue = 0;
              $(".selectedItem:checked").each(function () {
                let price = parseFloat($(this).closest("tr").find(".text-center:nth-child(5)").text().replace('₱', '').replace(',', '')); // Fetching the product price
                let availableQuantity = parseInt($(this).closest("tr").find(".text-center:nth-child(6)").text()); // Fetching available quantity

                let quantity = parseInt($(this).closest("tr").find(".refund-quantity").val()); // Fetching the refund quantity

                // Validate quantity against available quantity
                if (quantity > availableQuantity) {
                  $(this).closest("tr").find(".refund-quantity").val(availableQuantity);
                  quantity = availableQuantity; // Update quantity to available quantity
                }

                totalRefundValue += price * quantity; // Calculating total refund value
              });

              if (isNaN(totalRefundValue)) {
                $("#refund-TotalValue").text("₱0.00");
              } else {
                $("#refund-TotalValue").text(formatter.format(totalRefundValue));
              }
            }
          },
          error: function (xhr, status, error) {
            console.error("Error fetching transaction items: ", status, error);
          }
        });

        $("#transaction-viewModal").modal("show");
      },
      error: function (xhr, status, error) {
        console.error("Error fetching transaction: ", status, error);
      }
    });
  });
});
