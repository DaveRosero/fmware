$(document).ready(function () {
  // Function to fetch transactions via AJAX
  function fetchTransactions() {
    $.ajax({
      url: '/pos-fetchTransactions', // Replace with actual endpoint
      method: 'GET', // or 'POST' as per your backend implementation
      dataType: 'json', // Assuming JSON response from server
      success: function (response) {
        // Function to format date to month name and 12-hour time
        function formatDateTime(dateTime) {
          const date = new Date(dateTime);
          const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
          return date.toLocaleString('en-US', options);
        }
        // Initialize DataTable
        $('#transaction-table').DataTable({
          data: response.filter(transaction => !['void', 'prepared', 'to pay'].includes(transaction.status)).map(transaction => [
            transaction.pos_ref || transaction.order_ref,
            formatDateTime(transaction.date),
            '₱' + Number(transaction.total || transaction.gross).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','),
            transaction.name || '',
            '<span class="' + getStatusBadgeClass(transaction.status) + '">' + transaction.status + '</span>',
            '<button class="btn btn-primary view-transaction-btn" data-bs-posref="' + (transaction.pos_ref || transaction.order_ref) + '">View</button>'
          ]),
          columns: [
            { title: 'Transaction Ref' },
            { title: 'Transaction Date' },
            { title: 'Total' },
            { title: 'Name' },
            { title: 'Status' },
            { title: 'Actions' }
          ],
          order: [[1, "desc"]],
          destroy: true // Destroy the existing DataTable instance to recreate it
        });
        $('#transaction-table').on('click', '.view-transaction-btn', function () {
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
              $("#transactionViewLabel").text(data.transaction_type_source === 'order' ? "Order #" + data.pos_ref : "Transaction #" + data.pos_ref);
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
              } else if (data.transaction_type === "Online Order") {
                $("#customer-details").show();
                $("#rfName-input").val(data.firstname);
                $("#rlName-input").val(data.lastname);
                //merge house_no and street on online order
                let fullAddress = `${data.house_no} ${data.street}`; 
                $("#viewstreet-input, #street-label").val(fullAddress).show();
                $("#viewbrgy-input, #brgy-label").val(data.brgy).show();
                $("#viewmunicipality-input, #municipality-label").val(data.municipality).show();
                $("#viewcontact-input, #contact-label").val(data.phone).show();
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
                      selectCondition.val('Good');
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
                        case 'pending':
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
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error:", textStatus, errorThrown);
                    console.log("Response:", jqXHR.responseText);
                }
              });

              $("#transaction-viewModal").modal("show");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
          });
        });
      },
      error: function(jqXHR, textStatus, errorThrown) {
          console.log("Error:", textStatus, errorThrown);
          console.log("Response:", jqXHR.responseText);
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
      case 'pending':
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
    let isValid = true;

    // Validate reason
    if (refundReason === "") {
        isValid = false;
        $("#replace-refund-reason").addClass('is-invalid'); // Highlight invalid field
    } else {
        $("#replace-refund-reason").removeClass('is-invalid'); // Remove highlight if valid
    }

    // Initialize counters
    let totalInitialItems = 0;
    let totalRefundedItems = 0;
    let totalRemainingItems = 0;

    // Loop through all items in the transaction, including non-selected ones
    $(".selectedItem").each(function () {
        const product_id = $(this).data("product-id");
        const refund_qty = $(this).closest("tr").find(".refund-quantity").val();
        const condition = $(this).closest("tr").find(".item-condition").val();
        const initialQty = parseInt($(this).closest("tr").find(".text-center:nth-child(6)").text(), 10);
        
        // Only process if the item is selected
        if ($(this).is(":checked")) {
            // Validate condition and quantity
            if (condition === "") {
                isValid = false;
                $(this).closest("tr").find(".item-condition").addClass('is-invalid');
            } else {
                $(this).closest("tr").find(".item-condition").removeClass('is-invalid');
            }

            if (refund_qty <= 0 || isNaN(refund_qty)) {
                isValid = false;
                $(this).closest("tr").find(".refund-quantity").addClass('is-invalid');
            } else {
                $(this).closest("tr").find(".refund-quantity").removeClass('is-invalid');
            }

            refundItems.push({ product_id, refund_qty, condition });

            if (condition === "Good") {
                goodItemsCount += parseInt(refund_qty);
            } else if (condition === "Broken") {
                badItemsCount += parseInt(refund_qty);
            }

            totalRefundedItems += parseInt(refund_qty);
        }

        totalInitialItems += initialQty;
        totalRemainingItems += Math.max(0, initialQty - (refund_qty ? parseInt(refund_qty) : 0));
    });

    // Determine newStatus based on remaining items
    let newStatus = 'fully refunded';
    if (totalRemainingItems > 0) {
        newStatus = 'partially refunded';
    }

    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please ensure all selected items have a valid condition and quantity. Also, provide a reason for the refund.',
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
    const totalReplaceValue = $("#refund-TotalValue").text().replace(/[^\d.-]/g, '');
    const replacementReason = $("#replace-refund-reason").val();
    const replacedItems = [];
    let goodItemsCount = 0;
    let badItemsCount = 0;
    let isValid = true;

    // Validate reason
    if (replacementReason === "") {
        isValid = false;
        $("#replace-refund-reason").addClass('is-invalid'); // Highlight invalid field
    } else {
        $("#replace-refund-reason").removeClass('is-invalid'); // Remove highlight if valid
    }

    // Initialize counters
    let totalInitialItems = 0;
    let totalReplacedItems = 0;
    let totalRemainingItems = 0;

    // Loop through all items in the transaction, including non-selected ones
    $(".selectedItem").each(function () {
        const product_id = $(this).data("product-id");
        const refund_qty = parseInt($(this).closest("tr").find(".refund-quantity").val(), 10);
        const condition = $(this).closest("tr").find(".item-condition").val();
        const initialQty = parseInt($(this).closest("tr").find(".text-center:nth-child(6)").text(), 10);

        // Only process if the item is selected
        if ($(this).is(":checked")) {
            // Validate condition and quantity
            if (condition === "") {
                isValid = false;
                $(this).closest("tr").find(".item-condition").addClass('is-invalid');
            } else {
                $(this).closest("tr").find(".item-condition").removeClass('is-invalid');
            }

            if (refund_qty <= 0 || isNaN(refund_qty)) {
                isValid = false;
                $(this).closest("tr").find(".refund-quantity").addClass('is-invalid');
            } else {
                $(this).closest("tr").find(".refund-quantity").removeClass('is-invalid');
            }

            replacedItems.push({ product_id, refund_qty, condition });

            if (condition === "Good") {
                goodItemsCount += refund_qty;
            } else if (condition === "Broken") {
                badItemsCount += refund_qty;
            }

            totalReplacedItems += refund_qty;
        }

        totalInitialItems += initialQty;
        totalRemainingItems += Math.max(0, initialQty - (refund_qty ? refund_qty : 0));
    });

    // Determine newStatus based on remaining items
    let newStatus = 'fully replaced';
    if (totalRemainingItems > 0) {
        newStatus = 'partially replaced';
    }

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
            total_refund_value: totalReplaceValue,
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