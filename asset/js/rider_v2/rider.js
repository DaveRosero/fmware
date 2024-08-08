$(document).ready(function () {
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

  function fetchOrders() {
    $.ajax({
      url: "/model-order",
      type: "GET",
      dataType: "json",
      success: function (data) {
        // Initialize DataTable
        $("#orders-table").DataTable({
          data: data.map((order) => [
            order.order_ref || "N/A",
            formatDateTime(order.date) || "N/A",
            '<span class="' +
              getPaidStatusBadgeClass(order.paid) +
              '">' +
              (order.paid || "N/A") +
              "</span>",
            '<span class="badge ' +
              getStatusBadgeClass(order.status) +
              '">' +
              (order.status || "N/A") +
              "</span>",
            '<button class="btn btn-primary view-order-btn" data-order-ref="' +
              (order.order_ref || "") +
              '">View</button>',
          ]),
          columns: [
            { title: "Order Ref" },
            { title: "Date" },
            { title: "Paid" },
            { title: "Status" },
            { title: "Actions" },
          ],
          order: [[1, "desc"]], // Order by date descending
          destroy: true, // Destroy the existing DataTable instance to recreate it
        });
      },
      error: function (xhr, status, error) {
        console.error("Error fetching orders:", error);
      },
    });
  }

  function fetchOrderDetails(orderRef) {
    $.ajax({
      url: "/model-order-details",
      type: "GET",
      data: { order_ref: orderRef },
      dataType: "json",
      success: function (data) {
        console.log(data); // Debug: Check the actual data received

        if (!data) {
          console.error("No data found for order details.");
          return;
        }

        // Function to format price
        function formatPrice(price) {
          return "₱" + (parseFloat(price) || 0).toFixed(2);
        }

        // Initialize DataTable for order items
        $("#order-items-table").DataTable({
          data: data.items.map((item) => [
            item.product_name || "N/A",
            item.variant_name || "N/A",
            item.unit_name || "N/A",
            item.qty || 0,
            formatPrice(item.unit_price || 0),
            formatPrice(item.total_price || 0),
          ]),
          columns: [
            { title: "Product" },
            { title: "Variant" },
            { title: "Unit" },
            { title: "Quantity" },
            { title: "Unit Price" },
            { title: "Total Price" },
          ],
          destroy: true, // Destroy the existing DataTable instance to recreate it
        });

        // Populate order details
        $("#order-items-modal-label").text("Order: " + data.order_ref || "N/A");
        $("#order-date").text(formatDateTime(data.date) || "N/A");
        $("#order-paid").html(
          '<span class="' +
            getPaidStatusBadgeClass(data.paid) +
            '">' +
            (data.paid || "N/A") +
            "</span>"
        );
        $("#order-status").html(
          '<span class="badge ' +
            getStatusBadgeClass(data.status) +
            '">' +
            (data.status || "N/A") +
            "</span>"
        );
        $("#order-gross").text("₱" + (parseFloat(data.gross) || 0).toFixed(2));
        $("#order-delivery-fee").text(
          "₱" +
            (data.delivery_fee
              ? parseFloat(data.delivery_fee).toFixed(2)
              : "N/A")
        );
        $("#order-vat").text(
          "₱" + (data.vat ? parseFloat(data.vat).toFixed(2) : "N/A")
        );
        $("#order-discount").text(
          "₱" + (data.discount ? parseFloat(data.discount).toFixed(2) : "N/A")
        );

        // Populate additional order info
        $("#order-ref").text(data.order_ref || "N/A");
        $("#order-date").text(formatDateTime(data.date) || "N/A");
        $("#order-paid").html(
          '<span class="' +
            getPaidStatusBadgeClass(data.paid) +
            '">' +
            (data.paid || "N/A") +
            "</span>"
        );
        $("#order-status").html(
          '<span class="badge ' +
            getStatusBadgeClass(data.status) +
            '">' +
            (data.status || "N/A") +
            "</span>"
        );
        $("#order-gross").text("₱" + (parseFloat(data.gross) || 0).toFixed(2));
        $("#order-delivery-fee").text(
          "₱" +
            (data.delivery_fee
              ? parseFloat(data.delivery_fee).toFixed(2)
              : "N/A")
        );
        $("#order-vat").text(
          "₱" + (data.vat ? parseFloat(data.vat).toFixed(2) : "N/A")
        );
        $("#order-discount").text(
          "₱" + (data.discount ? parseFloat(data.discount).toFixed(2) : "N/A")
        );

        // Add user info
        $("#order-user-name").text(data.user_name || "N/A");
        $("#order-user-phone").text(data.user_phone || "N/A");

        // Add address info
        $("#order-address").html(
          (data.address.house_no ? data.address.house_no + ", " : "") +
            (data.address.street ? data.address.street + ", " : "") +
            (data.address.brgy ? data.address.brgy + ", " : "") +
            (data.address.municipality
              ? data.address.municipality + "<br>"
              : "")
        );
        $("#order-address-desc").html(
            (data.address.description || "N/A")
        );


        // Display the order items modal
        $("#order-items-modal").modal("show");
      },
      error: function (xhr, status, error) {
        console.error("Error fetching order details:", error);
      },
    });
  }

  // Fetch orders on page load
  fetchOrders();

  // Handle View button click
  $("#orders-table").on("click", ".view-order-btn", function () {
    var orderRef = $(this).data("order-ref");
    fetchOrderDetails(orderRef);
  });
});
