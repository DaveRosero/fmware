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

        // Show or hide customer details based on transaction type
        if (data.transaction_type === "Walk-in") {
          // Show only first name and last name, hide other details
          $("#customer-details").show();
          $("#viewfName-input").val(data.firstname);
          $("#viewlName-input").val(data.lastname);
          $("#viewstreet-input, #street-label").val("").hide();
          $("#viewbrgy-input, #brgy-label").val("").hide();
          $("#viewmunicipality-input, #municipality-label").val("").hide();
          $("#viewcontact-input, #contact-label").val("").hide();
          $("#viewdeliverer-input, #deliverer-label").val("").hide();
        } else if (data.transaction_type === "Delivery") {
          // Show all customer details
          $("#customer-details").show();
          $("#viewfName-input").val(data.firstname);
          $("#viewlName-input").val(data.lastname);

          // Split address into street, baranggay, municipality
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

        // Make AJAX call to fetch product details
        $.ajax({
          url: "/pos-historyprod",
          method: "GET",
          data: {
            pos_ref: posRef,
          },
          dataType: "html",
          success: function (data) {
            console.log(data);
            $("#productDetails").html(data);
          },
        });

        // Show the modal after updating all details
        $("#historyView").modal("show");
      },
    });
  });

  $(".void").on("click", function () {
    // Show a confirmation dialog
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
              showConfirmButton: false,
              timer: 1500,
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
