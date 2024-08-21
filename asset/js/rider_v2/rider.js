$(document).ready(function () {
  let riderId = null; // Declare a global variable

  // Function to retrieve rider ID
  function getRiderId() {
    $.ajax({
      url: "/model-get-riderId",
      type: "GET",
      dataType: "json",
      success: function (data) {
        if (data.success) {
          riderId = data.rider_id; // Assign to global variable
        } else {
          console.error("Failed to retrieve rider ID:", data.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error retrieving rider ID:", error);
      }
    });
  }


  // Fetch rider ID on page load
  getRiderId();
  // Function to format date
  function formatDateTime(dateTime) {
    const date = new Date(dateTime);
    const options = { year: "numeric", month: "long", day: "numeric" };
    return date.toLocaleDateString("en-US", options);
  }

  // Function to get status badge class
  function getStatusBadgeClass(status) {
    if (!status) return "badge text-bg-secondary"; // Default class for undefined or null status
    switch (status.toLowerCase()) {
      case "delivering":
        return "badge text-bg-primary"; // Class for delivering status
      case "delivered":
        return "badge text-bg-success"; // Class for delivered status
      case "pending":
        return "badge text-bg-warning"; // Class for pending status
      case "cancelled":
        return "badge text-bg-danger"; // Class for cancelled status
      default:
        return "badge text-bg-secondary"; // Default class
    }
  }

  // Function to get paid status badge class
  function getPaidStatusBadgeClass(paid) {
    if (!paid) return "badge text-bg-secondary"; // Default class for undefined or null paid status
    switch (paid.toLowerCase()) {
      case "paid":
        return "badge text-bg-success"; // Class for paid status
      case "unpaid":
        return "badge text-bg-danger"; // Class for unpaid status
      default:
        return "badge text-bg-secondary"; // Default class
    }
  }

  // Function to fetch and display orders
  function fetchOrders() {
    $.ajax({
      url: "/model-order",
      type: "GET",
      dataType: "json",
      success: function (data) {
        const filteredOrders = data.filter(order =>
          order.status.toLowerCase() === "pending" &&
          (!order.rider_id || order.rider_id === null)
        );

        $("#orders-table").DataTable({
          data: filteredOrders.map(order => [
            order.order_ref || "N/A",
            formatDateTime(order.date) || "N/A",
            `<span class="${getPaidStatusBadgeClass(order.paid)}">${order.paid || "N/A"}</span>`,
            `<span class="${getStatusBadgeClass(order.status)}">${order.status || "N/A"}</span>`,
            `<button class="btn btn-primary view-order-btn" data-order-ref="${order.order_ref || ""}">View</button>`
          ]),
          columns: [
            { title: "Order Ref" },
            { title: "Date" },
            { title: "Paid" },
            { title: "Status" },
            { title: "Actions" }
          ],
          order: [[1, "desc"]],
          destroy: true
        });
      },
      error: function (xhr, status, error) {
        console.error("Error fetching orders:", error);
      }
    });
  }

 // Function to fetch and display order details without using DataTables
function fetchOrderDetails(orderRef) {
  $.ajax({
    url: "/model-order-details",
    type: "GET",
    data: { order_ref: orderRef },
    dataType: "json",
    success: function (data) {
      if (!data) {
        console.error("No data found for order details.");
        return;
      }

      const formatPrice = (price) => `₱${(parseFloat(price) || 0).toFixed(2)}`;

      // Clear existing table rows
      const itemsTableBody = $("#order-items-table tbody");
      itemsTableBody.empty();

      // Generate rows for order items
      data.items.forEach(item => {
        const row = `
          <tr>
            <td>${item.product_name || "N/A"}</td>
            <td>${item.variant_name || "N/A"}</td>
            <td>${item.unit_name || "N/A"}</td>
            <td>${item.qty || 0}</td>
            <td>${formatPrice(item.unit_price)}</td>
            <td>${formatPrice(item.total_price)}</td>
          </tr>
        `;
        itemsTableBody.append(row);
      });

      // Populate other order details
      $("#order-items-modal-label").text(`Order: ${data.order_ref || "N/A"}`);
      $("#order-date").text(formatDateTime(data.date) || "N/A");
      $("#order-paid").html(`<span class="${getPaidStatusBadgeClass(data.paid)}">${data.paid || "N/A"}</span>`);
      $("#order-status").html(`<span class="badge ${getStatusBadgeClass(data.status)}">${data.status || "N/A"}</span>`);
      $("#order-gross").text(formatPrice(data.gross));
      $("#order-delivery-fee").text(formatPrice(data.delivery_fee) || "N/A");
      $("#order-vat").text(formatPrice(data.vat) || "N/A");
      $("#order-discount").text(formatPrice(data.discount) || "N/A");

      // Populate additional order info
      $("#order-user-name").text(data.user_name || "N/A");
      $("#order-user-phone").text(data.user_phone || "N/A");

      // Add address info
      $("#order-address").html(
        `${data.address.house_no ? data.address.house_no + ", " : ""}` +
        `${data.address.street ? data.address.street + ", " : ""}` +
        `${data.address.brgy ? data.address.brgy + ", " : ""}` +
        `${data.address.municipality ? data.address.municipality + "<br>" : ""}`
      );
      $("#order-address-desc").html(data.address.description || "N/A");

      // Display the order items modal
      $("#order-items-modal").modal("show");
    },
    error: function (xhr, status, error) {
      console.error("Error fetching order details:", error);
    }
  });
}


  // Fetch orders on page load
  fetchOrders();

  // Handle View button click
  $("#orders-table").on("click", ".view-order-btn", function () {
    const orderRef = $(this).data("order-ref");
    $("#accept-order-btn").data("order-ref", orderRef);
    fetchOrderDetails(orderRef);
  });

  // Handle Accept Order button click
  $("#accept-order-btn").on("click", function () {
    const orderRef = $(this).data("order-ref");

    // Check if order reference or rider ID is missing
    if (!orderRef || !riderId) {
      console.error("Order reference or rider ID is missing.");
      Swal.fire("Error", "Unable to accept the order. Missing order reference or rider ID.", "error");
      return;
    }

    // Use SweetAlert to confirm the action
    Swal.fire({
      title: "Are you sure?",
      text: "Do you want to accept this order?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, accept it!"
    }).then((result) => {
      if (result.isConfirmed) {
        // AJAX request to update the order status to 'delivering'
        $.ajax({
          url: "/model-update-order", // Update with your backend URL
          type: "POST",
          data: {
            order_ref: orderRef,
            rider_id: riderId,
            status: "delivering"
          },
          success: function (response) {
            try {
              const jsonResponse = typeof response === "string" ? JSON.parse(response) : response;
              if (jsonResponse.success) {
                Swal.fire("Success", "Order accepted successfully!", "success");
                fetchOrders(); // Refresh the orders table
                $("#order-items-modal").modal("hide"); // Hide the modal
                fetchAcceptedOrders(); // Refresh the accepted orders
              } else {
                Swal.fire("Error", "Failed to accept the order: " + jsonResponse.message, "error");
                console.error("Order update failed:", jsonResponse.message);
              }
            } catch (e) {
              console.error("Error parsing server response:", e);
              Swal.fire("Error", "An error occurred while accepting the order.", "error");
            }
          },
          error: function (xhr, status, error) {
            console.error("Error accepting the order:", error);
            Swal.fire("Error", "Failed to accept the order. Please try again.", "error");
          }
        });
      }
    });
  });


  // Function to fetch and display accepted orders
  function fetchAcceptedOrders() {
    $.ajax({
      url: "/model-acceptedOrder",
      type: "GET",
      dataType: "json",
      success: function (data) {
        const filteredOrders = data.filter(order =>
          order.status.toLowerCase() === "delivering" && order.rider_id == riderId
        );

        $("#acceptedOrder-table").DataTable({
          data: filteredOrders.map(order => [
            order.order_ref || "N/A",
            formatDateTime(order.date) || "N/A",
            `<span class="${getPaidStatusBadgeClass(order.paid)}">${order.paid || "N/A"}</span>`,
            `<span class="${getStatusBadgeClass(order.status)}">${order.status || "N/A"}</span>`,
            `<button class="btn btn-primary view-order-btn" data-order-ref="${order.order_ref || ""}">View</button>`
          ]),
          columns: [
            { title: "Order Ref" },
            { title: "Date" },
            { title: "Paid" },
            { title: "Status" },
            { title: "Actions" }
          ],
          order: [[1, "desc"]],
          destroy: true
        });
      },
      error: function (xhr, status, error) {
        console.error("Error fetching accepted orders:", error);
      }
    });
  }

 // Function to fetch and display accepted order details without using DataTables
function fetchAcceptedOrderDetails(orderRef) {
  $.ajax({
    url: "/model-acceptedOrder-details",
    type: "GET",
    data: { order_ref: orderRef },
    dataType: "json",
    success: function (data) {
      if (!data) {
        console.error("No data found for order details.");
        return;
      }

      const formatPrice = (price) => `₱${(parseFloat(price) || 0).toFixed(2)}`;

      // Clear existing table rows
      const itemsTableBody = $("#acceptedOrder-items-table tbody");
      itemsTableBody.empty();

      // Generate rows for order items
      data.items.forEach(item => {
        const row = `
          <tr>
            <td>${item.product_name || "N/A"}</td>
            <td>${item.variant_name || "N/A"}</td>
            <td>${item.unit_name || "N/A"}</td>
            <td>${item.qty || 0}</td>
            <td>${formatPrice(item.unit_price)}</td>
            <td>${formatPrice(item.total_price)}</td>
          </tr>
        `;
        itemsTableBody.append(row);
      });

      // Populate additional order information
      $("#acceptedOrder-items-modal-label").text(`Order: ${data.order_ref || "N/A"}`);
      $("#acceptedOrder-date").text(formatDateTime(data.date) || "N/A");
      $("#acceptedOrder-paid").html(`<span class="${getPaidStatusBadgeClass(data.paid)}">${data.paid || "N/A"}</span>`);
      $("#acceptedOrder-status").html(`<span class="badge ${getStatusBadgeClass(data.status)}">${data.status || "N/A"}</span>`);
      $("#acceptedOrder-gross").text(formatPrice(data.gross));
      $("#acceptedOrder-delivery-fee").text(formatPrice(data.delivery_fee) || "N/A");
      $("#acceptedOrder-vat").text(formatPrice(data.vat) || "N/A");
      $("#acceptedOrder-discount").text(formatPrice(data.discount) || "N/A");

      // Populate user and address info
      $("#acceptedOrder-user-name").text(data.user_name || "N/A");
      $("#acceptedOrder-user-phone").text(data.user_phone || "N/A");

      // Add address info
      $("#acceptedOrder-address").html(
        `${data.address.house_no ? data.address.house_no + ", " : ""}` +
        `${data.address.street ? data.address.street + ", " : ""}` +
        `${data.address.brgy ? data.address.brgy + ", " : ""}` +
        `${data.address.municipality ? data.address.municipality + "<br>" : ""}`
      );
      $("#acceptedOrder-address-desc").html(data.address.description || "N/A");

      // Display the order items modal
      $("#acceptedOrder-items-modal").modal("show");
    },
    error: function (xhr, status, error) {
      console.error("Error fetching accepted order details:", error);
    }
  });
}


  // Fetch accepted orders on page load
  fetchAcceptedOrders();

  // Handle View button click in accepted orders table
  $("#acceptedOrder-table").on("click", ".view-order-btn", function () {
    const orderRef = $(this).data("order-ref");
    fetchAcceptedOrderDetails(orderRef);
  });

  $('#cancelOrderButton').on('click', function () {
    const orderRef = $('#acceptedOrder-items-modal-label').text().replace('Order: ', ''); // Get the order reference from the modal

    // Check if order reference is missing
    if (!orderRef) {
      console.error("Order reference is missing.");
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Unable to cancel the order. Order reference is missing.'
      });
      return;
    }

    // Show confirmation before proceeding to cancel the order
    Swal.fire({
      title: 'Are you sure?',
      text: "Do you really want to cancel this order?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
      if (result.isConfirmed) {
        // Proceed with AJAX request to cancel the order
        $.ajax({
          url: '/model-cancelOrder', // Update with the correct backend URL
          type: 'POST',
          data: {
            order_ref: orderRef  // Send only the order reference to be canceled
          },
          success: function (response) {
            try {
              const res = typeof response === "string" ? JSON.parse(response) : response;
              if (res.success) {
                Swal.fire({
                  icon: 'success',
                  title: 'Order Canceled',
                  text: 'Order has been successfully canceled!'
                });
                $('#acceptedOrder-items-modal').modal('hide'); // Close the modal
                fetchAcceptedOrders(); // Refresh the accepted orders list
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Cancellation Failed',
                  text: 'Failed to cancel the order: ' + res.message
                });
                console.error("Order cancellation failed:", res.message);
              }
            } catch (e) {
              console.error("Error parsing server response:", e);
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while canceling the order.'
              });
            }
          },
          error: function (xhr, status, error) {
            console.error("Error canceling the order:", error);
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to cancel the order. Please try again.'
            });
          }
        });
      }
    });
  });

});
