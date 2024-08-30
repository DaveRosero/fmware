$(document).ready(function () {
  var historyTable = $("#history-search").DataTable({
    order: [[1, "desc"]], // Sort by second column (transaction date) in descending order
    stateSave: true, // Save the state of the table
  });

  function reset() {
    historyTable.order([[1, "desc"]]).draw();
    historyTable.search("").draw();
  }

  $("#history-search").on("click", ".view-history-btn", function () {
    const posRef = $(this).data("bs-posref");

    $.ajax({
      url: "/pos-history",
      method: "GET",
      data: {
        pos_ref: posRef,
      },
      dataType: "json",
      success: function (data) {
        console.log(data);

        // Number formatter for currency
        var formatter = new Intl.NumberFormat("en-PH", {
          style: "currency",
          currency: "PHP",
        });

        // Update general transaction details
        $("#historyViewLabel").text("Transaction #" + data.pos_ref);
        $("#transaction-date").text(data.date);
        $("#transaction-subtotal").text(
          formatter.format(Number(data.subtotal))
        );
        $("#htransaction-total").text(formatter.format(Number(data.total)));
        $("#viewdiscountRec-input").val(data.discount);
        $("#viewcashRec-input").val(Number(data.cash).toFixed(2));
        $("#history-change").text(formatter.format(Number(data.changes)));
        $("#transaction-status").text(data.status);
        $("#history-username").text(data.username);

        // Set status badge class based on status
        var statusBadge = $("#transaction-status");
        statusBadge.removeClass("text-bg-p rimary text-bg-secondary"); // Remove existing classes
        if (data.status === "paid") {
          statusBadge.addClass("text-bg-primary");
        } else if (data.status === "void") {
          statusBadge.addClass("text-bg-secondary");
        }

        // Enable or disable the "Void" button based on status
        if (data.status === "void") {
          $(".void").prop("disabled", true);
        } else if (data.status === "paid") {
          $(".void").prop("disabled", false);
        } else if (
          data.status === "fully refunded" ||
          data.status === "fully replaced"
        ) {
          $(".void").prop("disabled", true);
        }

        // Clear and set payment method
        $("#paymentMethod").empty();
        $("#paymentMethod").append(new Option("G-Cash", "G-Cash"));
        $("#paymentMethod").append(new Option("Cash", "Cash"));
        if (
          $("#paymentMethod option[value='" + data.payment_type + "']")
            .length === 0
        ) {
          $("#paymentMethod").append(
            $("<option>", {
              value: data.payment_type,
              text: data.payment_type,
            })
          );
        }
        $("#paymentMethod").val(data.payment_type);

        // Clear and set transaction type
        $("#history-transaction-type").empty();
        if (
          $(
            "#history-transaction-type option[value='" +
              data.transaction_type +
              "']"
          ).length === 0
        ) {
          $("#history-transaction-type").append(
            $("<option>", {
              value: data.transaction_type,
              text: data.transaction_type,
            })
          );
        }
        $("#history-transaction-type").val(data.transaction_type);

        if (data.transaction_type === "Walk-in") {
          $("#customer-details").show();
          $("#viewfName-input").val(data.firstname);
          $("#viewlName-input").val(data.lastname);
          $("#viewstreet-input, #street-label").val("").hide();
          $("#viewbrgy-input, #brgy-label").val("").hide();
          $("#viewmunicipality-input, #municipality-label").val("").hide();
          $("#viewcontact-input, #contact-label").val("").hide();
        } else if (data.transaction_type === "Delivery") {
          $("#customer-details").show();
          $("#viewfName-input").val(data.firstname);
          $("#viewlName-input").val(data.lastname);

          let addressParts = data.address.split(", ");
          let street = addressParts[0];
          let baranggay = addressParts[1];
          let municipality = addressParts[2];

          $("#viewstreet-input, #street-label").val(street).show();
          $("#viewbrgy-input, #brgy-label").val(baranggay).show();
          $("#viewmunicipality-input, #municipality-label")
            .val(municipality)
            .show();
          $("#viewcontact-input, #contact-label").val(data.contact_no).show();
        }

        $.ajax({
          url: "/pos-historyprod",
          method: "GET",
          data: {
            pos_ref: posRef,
          },
          success: function (data) {
            $("#productInfo").html(data);
          },
        });

        $("#historyView").modal("show");
      },
    });
  });

  $("#history-searchModal, #historyView").on("hidden.bs.modal", function () {
    reset();
  });

  // void button functionally
  $(".void").on("click", function (event) {
    event.preventDefault(); // Prevent default action if needed
  
    Swal.fire({
      title: "Are you sure you want to void this transaction?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, void it",
      cancelButtonText: "Cancel",
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        // Prompt user to enter a PIN
        Swal.fire({
          title: "Enter your PIN",
          input: "password",
          inputAttributes: {
            maxlength: 4, // Limit input to 4 digits
            pattern: "\\d{4}", // Ensure only 4 digits are entered
            placeholder: "PIN",
            autocapitalize: "off",
          },
          showCancelButton: true,
          confirmButtonText: "Submit",
          cancelButtonText: "Cancel",
          allowOutsideClick: false,
          preConfirm: (pin) => {
            if (!pin) {
              Swal.showValidationMessage("PIN is required");
              return false;
            }
            return pin; // Return the entered PIN
          },
        }).then((pinResult) => {
          if (pinResult.isConfirmed) {
            var posRef = $("#historyViewLabel").text().replace("Transaction #", "");
            var pin = pinResult.value; // The entered PIN
  
            // Proceed with the AJAX request to void the transaction
            $.ajax({
              url: "/pos-transvoid",
              method: "POST",
              data: {
                pos_ref: posRef,
                category: pin, // Include the PIN in the request
              },
              dataType: 'json',
              success: function (response) {
                if (response.success) {
                  // Transaction voided successfully
                  Swal.fire({
                    title: "Transaction Voided!",
                    text: "The transaction has been successfully voided.",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false,
                  }).then(() => {
                    // Redirect or perform any other action
                    window.location.href = "/pos";
                  });
                } else {
                  // PIN validation failed
                  Swal.fire({
                    title: "Invalid PIN",
                    text: "The PIN you entered is incorrect.",
                    icon: "error",
                  });
                }
              },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error:", textStatus, errorThrown);
                    console.log("Response:", jqXHR.responseText);
}
            });
          } else if (pinResult.isDenied) {
            // User clicked Cancel, log this event
            console.log("User clicked Cancel after PIN prompt");
          } else {
            // Handle other scenarios if needed
            console.log("Other result:", pinResult);
          }
        });
      } else if (result.isDenied) {
        // User clicked Cancel, log this event
        console.log("User clicked Cancel");
      } else {
        // Handle other scenarios if needed
        console.log("Other result:", result);
      }
    });
  });  
});
