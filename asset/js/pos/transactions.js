$(document).ready(function () {
  // Function to fetch transactions via AJAX
  function fetchTransactions() {
    $.ajax({
      url: '/pos-fetchTransactions', // Replace with actual endpoint
      method: 'GET', // or 'POST' as per your backend implementation
      dataType: 'json', // Assuming JSON response from server
      success: function (response) {
        // Clear existing table rows
        $('#transaction-table-body').empty();
        // Iterate through transactions and append rows
        response.forEach(function (transaction) {
          if (transaction.status !== 'void') {
            var row = '<tr>' +
              '<td>' + transaction.pos_ref + '</td>' +
              '<td>' + transaction.date + '</td>' +
              '<td>₱' + parseFloat(transaction.total).toFixed(2) + '</td>' +
              '<td>' + (transaction.name ? transaction.name : '') + '</td>' +
              '<td><span class="' + getStatusBadgeClass(transaction.status) + '">' + transaction.status + '</span></td>' +
              '<td><button class="btn btn-primary view-transaction-btn" data-bs-posref="' + transaction.pos_ref + '">View</button></td>' +
              '</tr>';
            $('#transaction-table-body').append(row);
          }
        })
        // Bind click event to view button (if not already bound)
        $('.view-transaction-btn').off('click').on('click', function () {
          var posRef = $(this).data('bs-posref');
          // Implement view transaction details functionality here
          $.ajax({
            url: "/pos-transactionDetails",
            method: "GET",
            data: {
              pos_ref: posRef,
            },
            dataType: "json",
            success: function (data) {
              transactionStatus = data.status;
              console.log("Transaction Data: ", data);
              var formatter = new Intl.NumberFormat("en-PH", {
                style: "currency",
                currency: "PHP",
              });
              $("#transactionViewLabel").text("Transaction #" + data.pos_ref);
              $("#transaction-date").text(data.date);
              $("#transaction-subtotal").text(formatter.format(Number(data.subtotal)));
              $("#rtransaction-total").text(formatter.format(Number(data.total)));
              $("#rviewdiscountRec-input").val(data.discount);
              $("#rviewcashRec-input").val(Number(data.cash).toFixed(2));
              $("#rtransaction-change").text(formatter.format(Number(data.changes)));
              $("#transaction-status").text(data.status);
              $("#rtrans-username").text(data.username);
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
              $("#rtransaction-type").empty();
              if ($("#rtransaction-type option[value='" + data.transaction_type + "']").length === 0) {
                $("#rtransaction-type").append($("<option>", {
                  value: data.transaction_type,
                  text: data.transaction_type,
                }));
              }
              $("#rtransaction-type").val(data.transaction_type);
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
                let municipality = addressParts[2]
                $("#viewstreet-input, #street-label").val(street).show();
                $("#viewbrgy-input, #brgy-label").val(baranggay).show();
                $("#viewmunicipality-input, #municipality-label").val(municipality).show();
                $("#viewcontact-input, #contact-label").val(data.contact_no).show();
                $("#viewdeliverer-input, #deliverer-label").val(data.deliverer_name).show();
              }
              //Geting Transaction Items
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
                    let isChecked = $(this).is(':checked');
                    let inputField = $(this).closest("tr").find(".refund-quantity");
                    let selectCondition = $(this).closest("tr").find(".item-condition");
                    inputField.val(isChecked ? 1 : 0);
                    inputField.prop("disabled", !isChecked);
                    if (isChecked) {
                      selectCondition.val('1');
                      selectCondition.prop("disabled", false);
                    } else {
                      selectCondition.val(''); // Reset select to default value when unchecked
                      selectCondition.prop("disabled", true);
                    }
                    updateRefundTotal();
                  });
                  $(".refund-quantity").change(function () {
                    updateRefundTotal();
                  });

                  // function toggleRefundButton() {
                  //   const anyItemsSelected = $(".selectedItem:checked").length > 0;
                  //   $("#refund-button").prop("disabled", !anyItemsSelected || transactionStatus === 'replaced');
                  // }
                  // function toggleReplaceButton() {
                  //   const anyItemsSelected = $(".selectedItem:checked").length > 0;
                  //   $("#replace-button").prop("disabled", !anyItemsSelected || transactionStatus === 'refunded');
                  // }

                  function toggleButtons() {
                    const anyItemsSelected = $(".selectedItem:checked").length > 0;
                    // Initially disable both buttons
                    $("#refund-button").prop("disabled", true);
                    $("#replace-button").prop("disabled", true);

                    if (anyItemsSelected) {
                      switch (transactionStatus) {
                        case 'paid':
                          $("#refund-button").prop("disabled", false);
                          $("#replace-button").prop("disabled", false);
                          break;
                        case 'refunded':
                        case 'fully refunded':
                        case 'partially refunded':
                          $("#refund-button").prop("disabled", false);
                          $("#replace-button").prop("disabled", true);
                          break;
                        case 'replaced':
                        case 'fully replaced':
                        case 'partially replaced':
                          $("#refund-button").prop("disabled", true);
                          $("#replace-button").prop("disabled", false);
                          break;
                        default:
                          break;
                      }

                    }
                  }

                  function toggleRefundReplaceReason() {
                    const anyItemsSelected = $(".selectedItem:checked").length > 0;
                    $("#replace-refund-reason").prop("disabled", !anyItemsSelected);
                  }
                  function updateRefundTotal() {
                    let totalRefundValue = 0;
                    $(".selectedItem:checked").each(function () {
                      let price = parseFloat($(this).closest("tr").find(".text-center:nth-child(5)").text().replace('₱', '').replace(',', '')); // Fetching the product price
                      let availableQuantity = parseInt($(this).closest("tr").find(".text-center:nth-child(6)").text()); // Fetching available quantity
                      let refundquantity = parseInt($(this).closest("tr").find(".refund-quantity").val()); // Fetching the refund quantity
                      // Validate quantity against available quantity
                      if (refundquantity > availableQuantity) {
                        $(this).closest("tr").find(".refund-quantity").val(availableQuantity);
                        refundquantity = availableQuantity; // Update quantity to available quantity
                      }
                      totalRefundValue += price * refundquantity; // Calculating total refund value
                    });
                    if (isNaN(totalRefundValue)) {
                      $("#refund-TotalValue").text("₱0.00");
                    } else {
                      $("#refund-TotalValue").text(formatter.format(totalRefundValue));
                    }
                    // toggleRefundButton();
                    // toggleReplaceButton();
                    toggleButtons()
                    toggleRefundReplaceReason();

                  }
                  // toggleRefundButton();
                  // toggleReplaceButton();
                  toggleButtons()
                  toggleRefundReplaceReason();

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
      },
      error: function (xhr, status, error) {
        console.error('Error fetching transactions:', error);
        // Handle error scenario
      }
    });
  }

  // Initial load of transactions on modal open
  $('#transaction-searchModal').on('shown.bs.modal', function () {
    fetchTransactions();
  });

  // Function to determine badge class based on status
  function getStatusBadgeClass(status) {
    var badgeClass = 'badge bg-secondary text-white';
    switch (status) {
      case 'paid':
        badgeClass = 'badge bg-primary text-white';
        break;
      case 'void':
        badgeClass = 'badge bg-secondary text-white';
        break;
      case 'refunded':
      case 'fully refunded':
        badgeClass = 'badge bg-success text-white';
        break;
      case 'replaced':
      case 'fully replaced':
        badgeClass = 'badge bg-info text-white';
        break;
      case 'partially refunded':
        badgeClass = 'badge bg-warning text-white';
        break;
      case 'partially replaced':
        badgeClass = 'badge bg-warning text-white';
        break;
    }
    return badgeClass;
  }
  let transactionStatus = '';
  //REFUND PROCESSING
  $("#refund-button").click(function () {
    const posRef = $("#transactionViewLabel").text().split("#")[1];
    const totalRefundValue = $("#refund-TotalValue").text().replace(/[^\d.-]/g, '');
    const refundReason = $("#replace-refund-reason").val();
    const refundItems = [];
    let goodItemsCount = 0;
    let badItemsCount = 0;
    let itemsRemaining = false;
    let newStatus = 'fully refunded';

    // Validate reason and items
    let isValid = true;
    if (refundReason === "") {
      isValid = false;
      $("#replace-refund-reason").addClass('is-invalid'); // Highlight invalid field
    } else {
      $("#replace-refund-reason").removeClass('is-invalid'); // Remove highlight if valid
    }

    $(".selectedItem:checked").each(function () {
      const product_id = $(this).data("product-id");
      const refund_qty = $(this).closest("tr").find(".refund-quantity").val();
      const condition = $(this).closest("tr").find(".item-condition").val();

      // Check if condition is selected
      if (condition === "") {
        isValid = false;
        $(this).closest("tr").find(".item-condition").addClass('is-invalid'); // Highlight invalid field
      } else {
        $(this).closest("tr").find(".item-condition").removeClass('is-invalid'); // Remove highlight if valid
      }

      refundItems.push({ product_id, refund_qty, condition });

      if (condition === "1") {
        goodItemsCount += parseInt(refund_qty);
      } else if (condition === "2") {
        badItemsCount += parseInt(refund_qty);
      }

      const remainingQty = parseInt($(this).closest("tr").find(".text-center:nth-child(6)").text()) - parseInt(refund_qty);
      if (remainingQty > 0) {
        itemsRemaining = true;
      }

      if (itemsRemaining) {
        newStatus = 'partially refunded';
      }
    });

    if (!isValid) {
      Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        text: 'Please select a condition for all selected items and provide a reason for the refund.',
      });
      return; // Stop further processing
    }

    $.ajax({
      url: "/pos-processRefund",
      method: "POST",
      data: {
        pos_ref: posRef,
        total_refund_value: totalRefundValue,
        refund_items: refundItems,
        refund_reason: refundReason,
        status: newStatus
      },
      success: function (response) {
        Swal.fire({
          icon: 'success',
          title: 'Refund Processed',
          html: `Refund processed successfully.<br>Good items refunded: ${goodItemsCount}<br>Bad items refunded: ${badItemsCount}`,
        });
        $("#transaction-status").text(newStatus);
        $("#transaction-viewModal").modal("hide");
        fetchTransactions();
      },
      error: function (xhr, status, error) {
        Swal.fire({
          icon: 'error',
          title: 'Refund Failed',
          text: 'Failed to process refund. Please try again.',
        });
      }
    });
  });

  $("#replace-button").click(function () {
    const posRef = $("#transactionViewLabel").text().split("#")[1];
    const totalRefundValue = $("#refund-TotalValue").text().replace(/[^\d.-]/g, '');
    const replacementReason = $("#replace-refund-reason").val();
    const replacedItems = [];
    let goodItemsCount = 0;
    let badItemsCount = 0;
    let itemsRemaining = false;
    let newStatus = 'fully replaced';

    // Validate reason and items
    let isValid = true;
    if (replacementReason === "") {
      isValid = false;
      $("#replace-refund-reason").addClass('is-invalid'); // Highlight invalid field
    } else {
      $("#replace-refund-reason").removeClass('is-invalid'); // Remove highlight if valid
    }

    $(".selectedItem:checked").each(function () {
      const product_id = $(this).data("product-id");
      const refund_qty = $(this).closest("tr").find(".refund-quantity").val();
      const condition = $(this).closest("tr").find(".item-condition").val();

      // Check if condition is selected
      if (condition === "") {
        isValid = false;
        $(this).closest("tr").find(".item-condition").addClass('is-invalid'); // Highlight invalid field
      } else {
        $(this).closest("tr").find(".item-condition").removeClass('is-invalid'); // Remove highlight if valid
      }

      replacedItems.push({ product_id, refund_qty, condition });

      if (condition === "1") {
        goodItemsCount += parseInt(refund_qty);
      } else if (condition === "2") {
        badItemsCount += parseInt(refund_qty);
      }

      const remainingQty = parseInt($(this).closest("tr").find(".text-center:nth-child(6)").text()) - parseInt(refund_qty);
      if (remainingQty > 0) {
        itemsRemaining = true;
      }

      if (itemsRemaining) {
        newStatus = 'partially replaced';
      }
    });

    if (!isValid) {
      Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        text: 'Please select a condition for all selected items and provide a reason for the replacement.',
      });
      return; // Stop further processing
    }

    $.ajax({
      url: "/pos-processReplace",
      method: "POST",
      data: {
        pos_ref: posRef,
        total_refund_value: totalRefundValue,
        replaced_items: replacedItems,
        replacement_reason: replacementReason,
        status: newStatus
      },
      success: function (response) {
        Swal.fire({
          icon: 'success',
          title: 'Replacement Processed',
          html: `Replacement processed successfully.<br>Good items replaced: ${goodItemsCount}<br>Bad items replaced: ${badItemsCount}`,
        });
        $("#transaction-status").text(newStatus);
        $("#transaction-viewModal").modal("hide");
        fetchTransactions();
      },
      error: function (xhr, status, error) {
        Swal.fire({
          icon: 'error',
          title: 'Replacement Failed',
          text: 'Failed to process replacement. Please try again.',
        });
      }
    });
  });
});