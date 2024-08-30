$(document).ready(function () {
  var pickupTable = $("#pickup-search").DataTable({
    order: [[1, "desc"]], // Adjust the index to match the date column
    stateSave: true,
    columnDefs: [
      { type: 'date', targets: 1 }, // Ensure the date column index is correct
    ],
  });

  function resetDataTable() {
    pickupTable.order([[1, "desc"]]).draw();
    pickupTable.search("").draw();
  }

  function updateChange() {
    var cashReceived = parseFloat($("#pickupcashRec-input").val()) || 0;
    var totalAmount = parseFloat($("#ptransaction-total").text().replace("₱", "").replace(/,/g, "")
    ) || 0;
  
    // Calculate the change
    var change = cashReceived - totalAmount;
    if (change < 0) {
      change = 0;
    }
    $("#pickup-change").text(
      `₱${change.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
    );
    validateupdateChange();
  }

  function validateupdateChange() {
    let cashReceived = parseFloat($("#pickupcashRec-input").val()) || 0;
    let totalAmount = parseFloat($("#ptransaction-total").text().replace("₱", "").replace(/,/g, "")) || 0;
  
    // Enable the claim button only if the cash received is greater than or equal to the total amount
    let isValid = cashReceived >= totalAmount;
  
    $(".claim").prop("disabled", !isValid);
  }
  

  $("#pickupcashRec-input").on("input", validateupdateChange);

  $("#pickupcashRec-input").on("input", updateChange);
  $("#ptransaction-total").on("DOMSubtreeModified", updateChange);
  updateChange(); 
  
  
  $("#pickup-searchModal, #pickupView").on("hidden.bs.modal", function () {
    resetDataTable();
  });

  // View pickup button functionality
  $("#pickup-search").on("click", ".view-pickup-btn", function () {
    var orderRef = $(this).data("bs-orderref");

    $.ajax({
      url: "/pos-pusearch",
      method: "GET",
      data: { order_ref: orderRef },
      dataType: "json",
      success: function (data) {
        console.log(data);

        var formatter = new Intl.NumberFormat("en-PH", {
          style: "currency",
          currency: "PHP",
        });

        var totalAmount = Number(data.gross) || 0;

        // Update transaction details
        $("#pickupViewLabel").text("Transaction #" + data.order_ref);
        $("#transaction-date").text(data.date);
        $("#transaction-subtotal").text(formatter.format(totalAmount));
        $("#transaction-status").text(data.status);
        $("#ptransaction-total").text(formatter.format(totalAmount));


        // Display cash and changes if status is claimed
        if (data.status === "claimed") {
          $("#pickupcashRec-input").val(data.cash);
          $("#pickup-change").text(formatter.format(data.changes));
        } else {
          $("#pickupcashRec-input").val('');
          $("#pickup-change").text(formatter.format(0));
        }


        // Handle visibility and button state
        if (data.status === "pending") {
          $("#payment-section").hide();
          $(".claim").hide();
          $(".prepared").show();
          $("#pickupcashRec-input").prop("disabled", false); // Enable input
        } else if (data.status === "claimed") {
          $("#payment-section").show();
          $(".claim").show();
          $(".prepared").hide();
          $("#pickupcashRec-input").prop("disabled", true); // Disable input
        } else {
          $("#payment-section").show();
          $(".claim").show();
          $(".prepared").hide();
          $("#pickupcashRec-input").prop("disabled", false); // Enable input for other statuses
        }

        // Set claim button state
        $(".claim").prop("disabled", true);

        // Set payment method
        $("#ptransactionStatus").empty();
        $("#ptransactionStatus").append(new Option("G-Cash", "G-Cash"));
        $("#ptransactionStatus").append(new Option("Cash", "Cash"));
        if (!$("#ptransactionStatus option[value='" + data.payment_type + "']").length) {
          $("#ptransactionStatus").append(new Option(data.payment_type, data.payment_type));
        }
        $("#ptransactionStatus").val(data.payment_type);

        // Set transaction type
        $("#pickupStatus").empty().append(new Option("Online Order", "Online Order")).val("Online Order");

        // Show and fill customer details
        $("#customer-details").show();
        $("#pickupfName-input").val(data.firstname);
        $("#pickuplName-input").val(data.lastname);
        $("#pickupcontact-input").val(data.phone);

        // Fetch product details
        $.ajax({
          url: "/pos-puprod",
          method: "GET",
          data: { order_ref: orderRef },
          success: function (data) {
            $("#productDetails").html(data);
          },
        });

        $("#pickupView").modal("show");
      },
    });
  });

  // Prepared button functionality
  $(".prepared").on("click", function (event) {
    event.preventDefault();

    Swal.fire({
      title: "Are You Sure Change Status Pending to Prepared?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, set to Prepared",
      cancelButtonText: "Cancel",
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        var orderRef = $("#pickupViewLabel").text().replace("Transaction #", "");

        $.ajax({
          url: "/pos-puprepare",
          method: "POST",
          data: { order_ref: orderRef },
          success: function (response) {
            Swal.fire({
              title: "Status Changed",
              text: "Status pending has been changed to prepared",
              icon: "success",
              timer: 1500,
              showConfirmButton: false,
            }).then(() => {
              window.location.href = "/pos";
            });
          },
        });
      } else {
        console.log("User clicked Cancel");
      }
    });
  });

  // Claim button functionality
  $(".claim").on("click", function (event) {
    event.preventDefault();

    Swal.fire({
      title: "Are You Sure, You want to claim this product?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, claim it",
      cancelButtonText: "Cancel",
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        var orderRef = $("#pickupViewLabel").text().replace("Transaction #", "");
        var cash = parseFloat($("#pickupcashRec-input").val()) || 0;
        var changes = parseFloat($("#pickup-change").text().replace("₱", "").replace(/,/g, "")) || 0;

        $.ajax({
          url: "/pos-puclaim",
          method: "POST",
          data: { order_ref: orderRef,
            cash: cash,
            changes: changes },
          success: function (response) {
            Swal.fire({
              title: "Transaction Claimed!",
              text: "The transaction has been successfully claimed.",
              icon: "success",
              timer: 1500,
              showConfirmButton: false,
            }).then(() => {
              window.location.href = "/pos";
            });
          },
        });
      } else {
        console.log("User clicked Cancel");
      }
    });
  });
});
