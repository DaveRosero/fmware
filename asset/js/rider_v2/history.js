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

  // Function to fetch and display history orders
  // function fetchHistoryOrders() {
  //   $.ajax({
  //     url: "/model-history",
  //     type: "GET",
  //     dataType: "json",
  //     success: function (data) {
  //       const filteredOrders = data.filter(order =>
  //         (order.status.toLowerCase() === "delivered" || order.status.toLowerCase() === "cancelled") &&
  //         order.rider_id == riderId
  //       );


  //       $("#historyOrder-table").DataTable({
  //         data: filteredOrders.map(order => [
  //           order.order_ref || "N/A",
  //           formatDateTime(order.date) || "N/A",
  //           `<span class="${getPaidStatusBadgeClass(order.paid)}">${order.paid || "N/A"}</span>`,
  //           `<span class="${getStatusBadgeClass(order.status)}">${order.status || "N/A"}</span>`,
  //           `<button class="btn btn-primary view-order-btn" data-order-ref="${order.order_ref || ""}">View</button>`
  //         ]),
  //         columns: [
  //           { title: "Order Ref" },
  //           { title: "Date" },
  //           { title: "Paid" },
  //           { title: "Status" },
  //           { title: "Actions" }
  //         ],
  //         order: [[1, "desc"]],
  //         destroy: true
  //       });
  //     },
  //     error: function (xhr, status, error) {
  //       console.error("Error fetching history orders:", error);
  //     }
  //   });
  // }
  function fetchHistoryOrders() {
    $.ajax({
      url: "/model-history",
      type: "GET",
      dataType: "json",
      success: function (data) {
        const filteredOrders = data.filter(order =>
          (order.status.toLowerCase() === "delivered" || order.status.toLowerCase() === "cancelled") &&
          order.rider_id == riderId
        );

        // Clear the container where history order cards will be appended
        $("#history-orders-container").empty();

        // Loop through the filtered orders and create Bootstrap cards
        filteredOrders.forEach(order => {
          const orderRef = order.order_ref || "N/A";
          const orderDate = formatDateTime(order.date) || "N/A";
          const paidStatus = `<span class="${getPaidStatusBadgeClass(order.paid)} me-2">${order.paid || "N/A"}</span>`;
          const orderStatus = `<span class="${getStatusBadgeClass(order.status)}">${order.status || "N/A"}</span>`;

          const cardBorderClass = order.status.toLowerCase() === "delivered" ? "border-success" : "border-danger";

          const orderCard = `
          <div class="card mb-3 ${cardBorderClass}">
            <div class="card-body">
              <div><strong>Order Ref:</strong> ${orderRef}</div>
              <div><strong>Date:</strong> ${orderDate}</div>
              <div class="d-flex mb-3">
                ${paidStatus} 
               ${orderStatus}
              </div>
              <div>
                <button class="btn btn-primary view-order-btn" data-order-ref="${orderRef}">View</button>
              </div>
            </div>
          </div>
        `;

          // Append the card to the container
          $("#history-orders-container").append(orderCard);
        });
      },
      error: function (xhr, status, error) {
        console.error("Error fetching history orders:", error);
      }
    });
  }

  // Function to fetch and display history order details without using DataTables
  function fetchHistoryOrderDetails(orderRef) {
    $.ajax({
      url: "/model-history-details",
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
        const itemsTableBody = $("#historyOrder-items-table tbody");
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
        $("#historyOrder-items-modal-label").text(`Order: ${data.order_ref || "N/A"}`);
        $("#historyOrder-date").text(formatDateTime(data.date) || "N/A");
        $("#historyOrder-paid").html(`<span class="${getPaidStatusBadgeClass(data.paid)}">${data.paid || "N/A"}</span>`);
        $("#historyOrder-status").html(`<span class="badge ${getStatusBadgeClass(data.status)}">${data.status || "N/A"}</span>`);
        $("#historyOrder-gross").text(formatPrice(data.gross));
        $("#historyOrder-delivery-fee").text(formatPrice(data.delivery_fee) || "N/A");
        $("#historyOrder-vat").text(formatPrice(data.vat) || "N/A");
        $("#historyOrder-discount").text(formatPrice(data.discount) || "N/A");

        // Populate additional order info
        $("#historyOrder-user-name").text(data.user_name || "N/A");
        $("#historyOrder-user-phone").text(data.user_phone || "N/A");

        // Add address info
        $("#historyOrder-address").html(
          `${data.address.house_no ? data.address.house_no + ", " : ""}` +
          `${data.address.street ? data.address.street + ", " : ""}` +
          `${data.address.brgy ? data.address.brgy + ", " : ""}` +
          `${data.address.municipality ? data.address.municipality + "<br>" : ""}`
        );
        $("#historyOrder-address-desc").html(data.address.description || "N/A");

        // Display the order items modal
        $("#historyOrder-items-modal").modal("show");
      },
      error: function (xhr, status, error) {
        console.error("Error fetching history order details:", error);
      }
    });
  }


  // Fetch history orders on page load
  fetchHistoryOrders();

  // Handle View button click in history orders table
  $("#history-orders-container").on("click", ".view-order-btn", function () {
    const orderRef = $(this).data("order-ref");
    fetchHistoryOrderDetails(orderRef);
  });


});
