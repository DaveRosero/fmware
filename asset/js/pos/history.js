$(document).ready(function () {
  $(".view-history-btn").click(function () {
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
        statusBadge.removeClass("text-bg-primary text-bg-secondary"); // Remove existing classes
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
          $("#viewdeliverer-input, #deliverer-label").val("").hide();
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
          $("#viewdeliverer-input, #deliverer-label")
            .val(data.deliverer_name)
            .show();
        }

        $.ajax({
          url: "/pos-historyprod",
          method: "GET",
          data: {
            pos_ref: posRef,
          },
          success: function (data) {
            $("#productDetails").html(data);
          },
        });

        $("#historyView").modal("show");
      },
    });
  });

  $(".void").on("click", function () {
    Swal.fire({
      title: "Are you sure you want to void this transaction?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, void it",
      cancelButtonText: "Cancel",
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        // Get the pos_ref from somewhere (possibly a data attribute or hidden input in your modal)
        var posRef = $("#historyViewLabel").text().replace("Transaction #", "");

        // Make an AJAX request to void the transaction
        $.ajax({
          url: "/pos-transvoid",
          method: "POST",
          data: { pos_ref: posRef },
          success: function (response) {
            console.log("Transaction voided successfully:", response);
            // Optionally, you can redirect or show a success message here
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
          },
          error: function (xhr, status, error) {
            console.error("Error voiding transaction:", status, error);
            // Show an error message or handle the error appropriately
            Swal.fire({
              title: "Error",
              text: "An error occurred while voiding the transaction. Please try again.",
              icon: "error",
              showConfirmButton: true,
            });
          },
        });
      }
    });
  });
});
