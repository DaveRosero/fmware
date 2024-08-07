$(document).ready(function () {
  // To sort the pickup order based on the transaction date
  var pickupTable = $("#pickup-search").DataTable({
    order: [[1, "desc"]],
    stateSave: true,
  });

  // Reset DataTable when the modal is closed
  $("#pickup-searchModal").on("hidden.bs.modal", function () {
    pickupTable.order([[1, "desc"]]).draw(); // Reset sorting order
    pickupTable.search("").draw(); // Clear search filter
  });

  $("#pickup-search").on("click", ".view-pickup-btn", function () {
    const orderRef = $(this).data("bs-orderref");

    $.ajax({
      url: "/pos-pusearch",
      method: "GET",
      data: {
        order_ref: orderRef,
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
        $("#pickupViewLabel").text("Transaction #" + data.order_ref);
        $("#transaction-date").text(data.date);
        $("#transaction-subtotal").text(
          formatter.format(Number(data.gross))
        );
        $("#transaction-status").text(data.status);
        $("#ptransaction-total").text(formatter.format(Number(data.gross)));
        $("#pickupcashRec-input").val(Number(data.cash).toFixed(2));
        $("#pickup-change").text(formatter.format(Number(data.changes)));
        $("#pickup-username").text(data.username);

        // Set status badge class based on status
        var statusBadge = $("#transaction-status");
        statusBadge.removeClass("text-bg-p rimary text-bg-secondary"); // Remove existing classes
        if (data.status === "paid") {
          statusBadge.addClass("text-bg-primary");
        } else if (data.status === "void") {
          statusBadge.addClass("text-bg-secondary");
        }

        // Enable or disable the "Void" button based on status
        if (data.status === "Claimed") {
          $(".claim").prop("disabled", true);
        } else if (data.status === "Not Claimed") {
          $(".claim").prop("disabled", false);
        } else if (
          data.status === "fully refunded" ||
          data.status === "fully replaced"
        ) {
          $(".claim").prop("disabled", true);
        }

        // Clear and set payment method
        $("#ptransactionStatus").empty();
        $("#ptransactionStatus").append(new Option("G-Cash", "G-Cash"));
        $("#ptransactionStatus").append(new Option("Cash", "Cash"));
        if (
          $("#ptransactionStatus option[value='" + data.payment_type + "']")
            .length === 0
        ) {
          $("#ptransactionStatus").append(
            $("<option>", {
              value: data.payment_type,
              text: data.payment_type,
            })
          );
        }
        $("#ptransactionStatus").val(data.payment_type);

        // Clear and set transaction type
        $("#pickupStatus").empty().append(new Option("Online Order", "Online Order"));
        $("#pickupStatus").val("Online Order");

        // Show and fill customer details
        $("#customer-details").show();
        $("#pickupfName-input").val(data.firstname);
        $("#pickuplName-input").val(data.lastname);
        $("#pickupcontact-input").val(data.phone);


        $.ajax({
          url: "/pos-puprod",
          method: "GET",
          data: {
            order_ref: orderRef,
          },
          success: function (data) {
            $("#productDetails").html(data);
          },
        });

        $("#pickupView").modal("show");
      },
    });
  });

  $("#pickupView").on("shown.bs.modal", function () {
    historyTable.order([[1, "desc"]]).draw();
  });

  $("#pickupView").on("hidden.bs.modal", function () {
    historyTable.order([[1, "desc"]]).draw(); // Reset sorting order
    historyTable.search("").draw(); // Clear search filter
  });

  // Reset DataTable when the modal is closed
  $("#pickup-searchModal").on("hidden.bs.modal", function () {
    historyTable.order([[1, "desc"]]).draw(); // Reset sorting order
    historyTable.search("").draw(); // Clear search filter
  });

  // void button functionally
  $(".claim").on("click", function (event) {
    event.preventDefault(); // Prevent default action if needed

    Swal.fire({
      title: "Are you sure you want to claim this product?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, claim it",
      cancelButtonText: "Cancel",
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        // User confirmed, proceed with voiding the transaction
        var posRef = $("#pickupViewLabel").text().replace("Transaction #", "");

        $.ajax({
          url: "/pos-transvoid",
          method: "POST",
          data: { order_ref: orderRef },
          success: function (response) {
            // Transaction voided successfully
            Swal.fire({
              title: "Transaction Claimed!",
              text: "The transaction has been successfully claimed.",
              icon: "success",
              timer: 1500,
              showConfirmButton: false,
            }).then(() => {
              // Redirect or perform any other action
              window.location.href = "/pos";
            });
          },
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
