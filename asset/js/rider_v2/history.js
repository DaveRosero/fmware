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
      },
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
  let historyOrders = []; // Store fetched history orders
  let historyPOS = []; // Store fetched history POS

  function fetchHistoryOrdersAndPOS() {
    $.when(
      $.ajax({
        url: "/model-history",
        type: "GET",
        dataType: "json",
      }),
      $.ajax({
        url: "/model-pos", // Endpoint to fetch POS history
        type: "GET",
        dataType: "json",
      })
    )
      .done(function (historyOrdersResponse, historyPOSResponse) {
        // Filter orders: delivered or canceled, with the correct rider ID
        historyOrders = historyOrdersResponse[0].filter(
          (order) =>
            (order.status.toLowerCase() === "delivered" ||
              order.status.toLowerCase() === "cancelled") &&
            order.rider_id == riderId
        );

        // Filter POS: delivered or canceled, with the correct rider ID
        historyPOS = historyPOSResponse[0].filter(
          (pos) =>
            (pos.status.toLowerCase() === "delivered" ||
              pos.status.toLowerCase() === "cancelled") &&
            pos.rider_id == riderId
        );

        displayHistoryOrdersAndPOS(); // Display both Orders and POS
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.error(
          "Error fetching history orders or POS:",
          textStatus,
          errorThrown
        );
      });
  }

  function displayHistoryOrdersAndPOS(orders = historyOrders, pos = historyPOS) {
    const container = $("#history-orders-container");
    container.empty(); // Clear the container before appending new history

    // Display History Orders
    orders.forEach((order) => {
      const orderRef = order.order_ref || "N/A";
      const orderDate = formatDateTime(order.date) || "N/A";
      const paidStatus = `<span class="${getPaidStatusBadgeClass(
        order.paid
      )} me-2">${order.paid || "N/A"}</span>`;
      const orderStatus = `<span class="${getStatusBadgeClass(order.status)}">${order.status || "N/A"
        }</span>`;

      const cardBorderClass =
        order.status.toLowerCase() === "delivered"
          ? "border-success"
          : "border-danger";

      const orderCard = `
              <div class="card mb-3 ${cardBorderClass}">
                  <div class="card-body">
                      <div><strong>Order Ref:</strong> ${orderRef}</div>
                      <div><strong>Date:</strong> ${orderDate}</div>
                      <div class="d-flex mb-2">
                          ${paidStatus} 
                          ${orderStatus}
                      </div>
                      <div>
                          <button class="btn btn-primary view-order-btn" data-order-ref="${orderRef}">View Order</button>
                      </div>
                  </div>
              </div>
          `;

      container.append(orderCard); // Append each order card to the container
    });

    // Display History POS
    pos.forEach((pos) => {
      const posRef = pos.pos_ref || "N/A";
      const posDate = formatDateTime(pos.date) || "N/A";
      const paidStatus = `<span class="${getPaidStatusBadgeClass(
        pos.paid
      )} me-2">${pos.paid || "N/A"}</span>`;
      const posStatus = `<span class="${getStatusBadgeClass(pos.status)}">${pos.status || "N/A"
        }</span>`;

      const cardBorderClass =
        pos.status.toLowerCase() === "delivered"
          ? "border-success"
          : "border-danger";

      const posCard = `
              <div class="card mb-3 ${cardBorderClass}">
                  <div class="card-body">
                      <div><strong>POS Ref:</strong> ${posRef}</div>
                      <div><strong>Date:</strong> ${posDate}</div>
                      <div class="d-flex mb-2">
                          ${paidStatus} 
                          ${posStatus}
                      </div>
                      <div>
                          <button class="btn btn-primary view-pos-btn" data-pos-ref="${posRef}">View POS</button>
                      </div>
                  </div>
              </div>
          `;

      container.append(posCard); // Append each POS card to the container
    });

    // Attach event listeners for view buttons
    attachEventListeners();
  }

  // Search functionality: Filter orders and POS by their reference (order_ref or pos_ref)
  $("#search-input").on("input", function () {
    const searchTerm = $(this).val().toLowerCase();

    // Filter history orders by order_ref
    const searchedOrders = historyOrders.filter((order) =>
      order.order_ref.toLowerCase().includes(searchTerm)
    );

    // Filter history POS by pos_ref
    const searchedPOS = historyPOS.filter((pos) =>
      pos.pos_ref.toLowerCase().includes(searchTerm)
    );

    displayHistoryOrdersAndPOS(searchedOrders, searchedPOS); // Display filtered orders and POS
  });

  // Sort functionality: Sort orders and POS based on selected criteria
  function sortHistoryOrdersAndPOS(criteria) {
    let displayOrders = [];
    let displayPOS = [];

    // Determine what to display based on criteria
    if (criteria === "Order Ref") {
      displayOrders = [...historyOrders].sort((a, b) => {
        return a.order_ref.localeCompare(b.order_ref); // Sort by Order Ref
      });
      displayPOS = []; // No POS to display
    } else if (criteria === "POS Ref") {
      displayOrders = []; // No orders to display
      displayPOS = [...historyPOS].sort((a, b) => {
        return a.pos_ref.localeCompare(b.pos_ref); // Sort by POS Ref
      });
    } else if (criteria === "Date") {
      displayOrders = [...historyOrders].sort((a, b) => {
        return new Date(b.date) - new Date(a.date); // Sort by newest date first
      });
      displayPOS = [...historyPOS].sort((a, b) => {
        return new Date(b.date) - new Date(a.date); // Sort by newest date first
      });
    } else if (criteria === "Cancelled") {
      displayOrders = [...historyOrders].filter(order => order.status === "cancelled")
        .sort((a, b) => new Date(b.date) - new Date(a.date)); // Newest first
      displayPOS = [...historyPOS].filter(posItem => posItem.status === "cancelled")
        .sort((a, b) => new Date(b.date) - new Date(a.date)); // Newest first
    }
    else if (criteria === "Delivered") {
      displayOrders = [...historyOrders].filter(order => order.status === "delivered")
        .sort((a, b) => new Date(b.date) - new Date(a.date)); // Newest first
      displayPOS = [...historyPOS].filter(posItem => posItem.status === "delivered")
        .sort((a, b) => new Date(b.date) - new Date(a.date)); // Newest first
    } else if (criteria === "All") {
      displayOrders = [...historyOrders]; // Show all orders
      displayPOS = [...historyPOS]; // Show all POS
    }

    displayHistoryOrdersAndPOS(displayOrders, displayPOS); // Display results
  }

  // Sorting functionality: Trigger when a sort option is selected
  $(".dropdown-menu .dropdown-item").on("click", function (e) {
    e.preventDefault();
    const sortBy = $(this).data("sort"); // Get the data-sort attribute value
    sortHistoryOrdersAndPOS(sortBy); // Sort orders and POS based on criteria
  });


  // Sorting functionality: Trigger when a sort option is selected
  $(".dropdown-menu .dropdown-item").on("click", function (e) {
    e.preventDefault();
    const sortBy = $(this).data("sort"); // Get the data-sort attribute value
    sortHistoryOrdersAndPOS(sortBy); // Sort orders and POS based on criteria
  });
  attachEventListeners();
  // Initial fetch
  fetchHistoryOrdersAndPOS();


  // Attach event listeners for viewing order or POS details
  function attachEventListeners() {
    // Order view button
    $("#history-orders-container").on("click", ".view-order-btn", function () {
      const orderRef = $(this).data("order-ref");
      fetchHistoryOrderDetails(orderRef); // Function to fetch and show order details
    });

    // POS view button
    $("#history-orders-container").on("click", ".view-pos-btn", function () {
      const posRef = $(this).data("pos-ref");
      fetchHistoryPOSDetails(posRef); // Function to fetch and show POS details
    });
  }

  // Fetch history order details (implementation similar to before)
  function fetchHistoryOrderDetails(orderRef) {
    $.ajax({
      url: "/model-history-details",
      type: "GET",
      data: { order_ref: orderRef },
      dataType: "json",
      success: function (data) {
        // Show order details in a modal
        console.log("Order Details:", data);
        // Implement modal display logic here
      },
      error: function (xhr, status, error) {
        console.error("Error fetching order details:", error);
      },
    });
  }

  // Fetch history POS details (similar to fetchHistoryOrderDetails)
  function fetchHistoryPOSDetails(posRef) {
    $.ajax({
      url: "/model-pos-details",
      type: "GET",
      data: { pos_ref: posRef },
      dataType: "json",
      success: function (data) {
        // Show POS details in a modal
        console.log("POS Details:", data);
        // Implement modal display logic here
      },
      error: function (xhr, status, error) {
        console.error("Error fetching POS details:", error);
      },
    });
  }

  // Fetch history orders and POS on page load
  fetchHistoryOrdersAndPOS();

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

        const formatPrice = (price) =>
          `₱${(parseFloat(price) || 0).toFixed(2)}`;

        // Clear existing order items
        const itemsContainer = $("#historyOrder-items-container");
        itemsContainer.empty();

        let subtotal = 0;

        // Generate and append items to the container, calculate subtotal
        data.items.forEach((item) => {
          const itemTotal = parseFloat(item.total_price) || 0;
          subtotal += itemTotal;

          const itemHtml = `
          <div class="d-flex justify-content-between py-2">
            <div>
              <p class="mb-0"><strong>${item.product_name || "N/A"}</strong></p>
              <p class="mb-0">${formatPrice(item.unit_price)} (${item.variant_name || "N/A"
            }, ${item.unit_name || "N/A"}) x ${item.qty || 0}</p>
            </div>
            <p class="mb-0">${formatPrice(itemTotal)}</p>
          </div>
        `;
          itemsContainer.append(itemHtml);
        });

        // Calculate grand total
        const deliveryFee = parseFloat(data.delivery_fee) || 0;
        const vat = parseFloat(data.vat) || 0;
        const discount = parseFloat(data.discount) || 0;
        const grandTotal = subtotal + deliveryFee + vat - discount;

        // Populate other order details
        $("#historyOrder-items-modal-label").text(
          `Order: ${data.order_ref || "N/A"}`
        );
        $("#historyOrder-date").text(formatDateTime(data.date) || "N/A");
        $("#historyOrder-paid").html(
          `<span class="${getPaidStatusBadgeClass(data.paid)}">${data.paid || "N/A"
          }</span>`
        );
        $("#historyOrder-status").html(
          `<span class="badge ${getStatusBadgeClass(data.status)}">${data.status || "N/A"
          }</span>`
        );
        $("#historyOrder-subtotal").text(formatPrice(subtotal));
        $("#historyOrder-delivery-fee").text(formatPrice(deliveryFee) || "N/A");
        $("#historyOrder-vat").text(formatPrice(vat) || "N/A");
        $("#historyOrder-discount").text(formatPrice(discount) || "N/A");
        $("#historyOrder-grand-total").text(formatPrice(grandTotal));

        // Populate user and address info
        $("#historyOrder-user-name").text(data.user_name || "N/A");
        $("#historyOrder-user-phone").text(data.user_phone || "N/A");

        // Add address info
        $("#historyOrder-address").html(
          `${data.address.house_no ? data.address.house_no + ", " : ""}` +
          `${data.address.street ? data.address.street + ", " : ""}` +
          `${data.address.brgy ? data.address.brgy + ", " : ""}` +
          `${data.address.municipality
            ? data.address.municipality + "<br>"
            : ""
          }`
        );
        $("#historyOrder-address-desc").html(data.address.description || "N/A");

        // Display the order items modal
        $("#historyOrder-items-modal").modal("show");
      },
      error: function (xhr, status, error) {
        console.error("Error fetching history order details:", error);
      },
    });
  }

  function fetchHistoryPOSDetails(posRef) {
    $.ajax({
      url: "/model-acceptedPos-details",
      type: "GET",
      data: { pos_ref: posRef },
      dataType: "json",
      success: function (data) {
        console.log(data);
        if (!data) {
          console.error("No data found for POS details.");
          return;
        }

        const formatPrice = (price) =>
          `₱${(parseFloat(price) || 0).toFixed(2)}`;

        // Clear existing POS items
        const itemsContainer = $("#historyPOS-items-container");
        itemsContainer.empty();

        let subtotal = 0;

        // Generate and append items to the container, calculate subtotal
        data.items.forEach((item) => {
          const itemTotal = parseFloat(item.total_price) || 0;
          subtotal += itemTotal;

          const itemHtml = `
                <div class="d-flex justify-content-between py-2">
                    <div>
                        <p class="mb-0"><strong>${item.product_name || "N/A"
            }</strong></p>
                        <p class="mb-0">${formatPrice(item.unit_price)} (${item.variant_name || "N/A"
            }, ${item.unit_name || "N/A"}) x ${item.qty || 0}</p>
                    </div>
                    <p class="mb-0">${formatPrice(itemTotal)}</p>
                </div>
            `;
          itemsContainer.append(itemHtml);
        });

        // Calculate grand total
        const deliveryFee = parseFloat(data.delivery_fee) || 0;
        const vat = parseFloat(data.vat) || 0;
        const discount = parseFloat(data.discount) || 0;
        const grandTotal = subtotal + deliveryFee + vat - discount;

        // Populate other POS details
        $("#historyPOS-items-modal-label").text(
          `POS: ${data.pos_ref || "N/A"}`
        );
        $("#historyPOS-date").text(formatDateTime(data.date) || "N/A");
        $("#historyPOS-user-name").text(data.user_name || "N/A");
        $("#historyPOS-user-phone").text(data.user_phone || "N/A");
        // Set address as a Google Maps link
        const address = data.address || "N/A";
        const googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
        $("#historyPOS-address").html(`<a href="${googleMapsUrl}" target="_blank">${address}</a>`);
        

        $("#historyPOS-paid").html(
          `<span class="${getPaidStatusBadgeClass(data.paid)}">${data.paid || "N/A"
          }</span>`
        );
        $("#historyPOS-status").html(
          `<span class="badge ${getStatusBadgeClass(data.status)}">${data.status || "N/A"
          }</span>`
        );
        $("#historyPOS-subtotal").text(formatPrice(subtotal));
        $("#historyPOS-delivery-fee").text(formatPrice(deliveryFee) || "N/A");
        $("#historyPOS-vat").text(formatPrice(vat) || "N/A");
        $("#historyPOS-discount").text(formatPrice(discount) || "N/A");
        $("#historyPOS-grand-total").text(formatPrice(grandTotal));

        // Display the POS items modal
        $("#historyPOS-items-modal").modal("show");
      },
      error: function (xhr, status, error) {
        console.error("Error fetching history POS details:", error);
      },
    });
  }


});
