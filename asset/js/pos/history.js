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

        // Update general transaction details
        $("#historyViewLabel").text("Transaction #" + data.pos_ref);
        $("#transaction-date").text(data.date);
        $("#transaction-subtotal").text(data.subtotal);
        $("#htransaction-total").text("₱" + data.total);
        $("#transaction-discount").text(data.discount);
        $("#viewcashRec-input").val(data.cash);
        $("#history-change").text(data.changes);
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
});
