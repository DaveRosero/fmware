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
    return date.toLocaleDateString("en-PH", options);
  }
  function formatPrice(price) {
    return `₱${(parseFloat(price) || 0).toFixed(2)}`;
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
  let orders = []; // Global variable to store fetched orders
  let pos = []; // Global variable to store fetched POS
  // Fetch Orders and POS from the server
  function fetchOrdersAndPOS() {
    $.when(
      $.ajax({
        url: "/model-order",
        type: "GET",
        dataType: "json",
      }),
      $.ajax({
        url: "/model-pos", // Separate route to fetch POS data
        type: "GET",
        dataType: "json",
      })
    )
      .done(function (ordersResponse, posResponse) {
        orders = ordersResponse[0].filter(
          (order) =>
            order.status.toLowerCase() === "pending" && // Status is pending
            (!order.rider_id || order.rider_id === null) && // No assigned rider
            (
              // Payment type 2 and paid
              (order.payment_type_name.toLowerCase() === "gcash" && order.paid.toLowerCase() === "paid") ||
              // Payment type 1 and paid or unpaid
              (order.payment_type_name.toLowerCase() === "cod" &&
                (order.paid.toLowerCase() === "paid" || order.paid.toLowerCase() === "unpaid"))
            )
        );




        pos = posResponse[0].filter(
          (posItem) =>
            posItem.status.toLowerCase() === "pending" && // Status is pending
            (!posItem.rider_id || posItem.rider_id === null) && // No assigned rider
            (posItem.paid.toLowerCase() === "paid" ||
              posItem.paid.toLowerCase() === "unpaid") // Paid or Unpaid POS
        );

        displayOrdersAndPOS(orders, pos); // Display both Orders and POS
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.error("Error fetching orders or POS:", textStatus, errorThrown);
      });
  }
  // Search functionality: Filter Orders and POS by reference (order_ref or pos_ref)
  $("#search-input").on("input", function () {
    const searchTerm = $(this).val().toLowerCase();

    // Filter orders by order_ref
    const filteredOrders = orders.filter((order) =>
      order.order_ref.toLowerCase().includes(searchTerm)
    );

    // Filter POS by pos_ref
    const filteredPOS = pos.filter((posItem) =>
      posItem.pos_ref.toLowerCase().includes(searchTerm)
    );

    displayOrdersAndPOS(filteredOrders, filteredPOS); // Display filtered results
    attachEventListeners()
  });
  // Sort Orders and POS based on selected criteria
  function sortOrdersAndPOS(criteria) {
    const sortedOrders = [...orders].sort((a, b) => {
      if (criteria === "Order Ref") {
        return a.order_ref.localeCompare(b.order_ref);
      } else if (criteria === "Date") {
        return new Date(b.date) - new Date(a.date); // Newest date first
      } else if (criteria === "Paid") {
        return a.paid.localeCompare(b.paid);
      }
    });

    const sortedPOS = [...pos].sort((a, b) => {
      if (criteria === "POS Ref") {
        return a.pos_ref.localeCompare(b.pos_ref);
      } else if (criteria === "Date") {
        return new Date(b.date) - new Date(a.date); // Newest date first
      } else if (criteria === "Paid") {
        return a.paid.localeCompare(b.paid);
      }
    });

    displayOrdersAndPOS(sortedOrders, sortedPOS); // Display sorted results
  }
  // Sorting functionality: Trigger when a sort option is selected
  $(".dropdown-menu .dropdown-item").on("click", function (e) {
    e.preventDefault();
    const sortBy = $(this).data("sort"); // Get the data-sort attribute value
    sortOrdersAndPOS(sortBy); // Sort Orders and POS based on criteria
  });
  // Display both Orders and POS cards
  function displayOrdersAndPOS(filteredOrders, filteredPOS) {
    const container = $("#orders-container");
    container.empty(); // Clear the container

    // Display Orders
    filteredOrders.forEach((order) => {
      const orderRef = order.order_ref || "N/A";
      const orderDate = formatDateTime(order.date) || "N/A";
      const paidStatus = `<span class="${getPaidStatusBadgeClass(order.paid)} me-2">${order.paid || "N/A"}</span>`;
      const deliveryStatus = `<span class="${getStatusBadgeClass(order.status)}">${order.status || "N/A"}</span>`;

      const orderCard = `
        <div class="card mb-3">
          <div class="card-body">
            <div><strong>Order Ref:</strong> ${orderRef}</div>
            <div><strong>Date:</strong> ${orderDate}</div>
            <div class="d-flex mb-2">
              ${paidStatus} 
              ${deliveryStatus}
            </div>
            <div>
              <button class="btn btn-primary view-order-btn" data-type="order" data-ref="${orderRef}">View Order</button>
            </div>
          </div>
        </div>
      `;
      container.append(orderCard);
    });

    // Display POS
    filteredPOS.forEach((posItem) => {
      const posRef = posItem.pos_ref || "N/A";
      const posDate = formatDateTime(posItem.date) || "N/A";
      const paidStatus = `<span class="${getPaidStatusBadgeClass(posItem.paid)} me-2">${posItem.paid || "N/A"}</span>`;
      const deliveryStatus = `<span class="${getStatusBadgeClass(posItem.status)}">${posItem.status || "N/A"}</span>`;

      const posCard = `
        <div class="card mb-3">
          <div class="card-body">
            <div><strong>POS Ref:</strong> ${posRef}</div>
            <div><strong>Date:</strong> ${posDate}</div>
            <div class="d-flex mb-2">
              ${paidStatus} 
              ${deliveryStatus}
            </div>
            <div>
              <button class="btn btn-primary view-pos-btn" data-type="pos" data-ref="${posRef}">View POS</button>
            </div>
          </div>
        </div>
      `;
      container.append(posCard);
    });
    attachEventListeners()
  }
  // Attach event listeners to the view buttons
  function attachEventListeners() {
    // Order view button
    $("#orders-container").on("click", ".view-order-btn", function () {
      const orderRef = $(this).data("ref");
      $("#accept-order-btn").data("order-ref", orderRef);
      fetchOrderDetails(orderRef);
    });

    // POS view button
    $("#orders-container").on("click", ".view-pos-btn", function () {
      const posRef = $(this).data("ref");
      $("#accept-pos-btn").data("pos-ref", posRef);
      fetchPOSDetails(posRef); // Show POS modal when "View POS" is clicked
    });
  }
  // Fetch Order details and display in modal
  function fetchOrderDetails(orderRef) {
    $.ajax({
      url: "/model-order-details",
      type: "GET",
      data: { order_ref: orderRef },
      dataType: "json",
      success: function (data) {
        populateOrderModal(data);
        $("#order-items-modal").modal("show");
      },
      error: function (xhr, status, error) {
        console.error("Error fetching order details:", error);
        console.log("Response text:", xhr.responseText); // Log the respons
      },
    });
  }
  // Fetch POS details and display in modal
  function fetchPOSDetails(posRef) {
    $.ajax({
      url: "/model-pos-details",
      type: "GET",
      data: { pos_ref: posRef },
      dataType: "json",
      success: function (data) {
        populatePOSModal(data);
        $("#pos-items-modal").modal("show");
      },
      error: function (xhr, status, error) {
        console.error("Error fetching POS details:", error);
      },
    });
  }
  // Populate the order modal with details
  function populateOrderModal(order) {
    // Populate modal with order details
    $("#order-date").text(`Date: ${formatDateTime(order.date)}`);
    $("#order-user-name").text(order.user_name || "N/A");
    $("#order-user-phone").text(order.user_phone || "N/A");
    $("#order-address").text(
      `${order.address.house_no || "N/A"} ${order.address.street || "N/A"}, ${order.address.brgy || "N/A"
      }, ${order.address.municipality || "N/A"}`
    );
    $("#order-address-desc").text(order.address.description || "N/A");

    // Clear previous items
    $("#order-items-container").empty();

    // Populate order items
    order.items.forEach((item) => {
      $("#order-items-container").append(`
      <tr>
        <td>${item.product_name || "N/A"}</td>
        <td>${item.qty || 0}</td>
        <td>${formatPrice(item.total_price || 0)}</td>
      </tr>
    `);
    });

    // Calculate subtotal and grand total
    const subtotal = order.gross - (order.delivery_fee || 0);
    const grandTotal =
      subtotal - (order.discount || 0) + (order.delivery_fee || 0);

    // Populate price section
    $("#order-gross").text(formatPrice(subtotal || 0));
    $("#order-vat").text(formatPrice(order.vat || 0));
    $("#order-discount").text(formatPrice(order.discount || 0));
    $("#order-delivery-fee").text(formatPrice(order.delivery_fee || 0));
    $("#order-grand-total").text(formatPrice(grandTotal || 0));
  }
  // Populate the POS modal with details
  function populatePOSModal(pos) {
    // Populate modal with POS details
    $("#pos-date").text(`${formatDateTime(pos.date)}`);

    // Combine firstname and lastname for user name display
    $("#pos-user-name").text(`${pos.firstname || "N/A"} ${pos.lastname || "N/A"}`);

    // Show contact number from pos table
    $("#pos-user-phone").text(pos.contact_no || "N/A");

    // Show address from pos table
    $("#pos-address").text(`${pos.address || "N/A"}`);

    // Clear previous items
    $("#pos-items-container").empty();

    // Populate POS items
    pos.items.forEach((item) => {
      $("#pos-items-container").append(`
      <tr>
        <td>${item.product_name || "N/A"}</td>
        <td>X${item.qty || 0}</td>
        <td>${formatPrice(item.total || 0)}</td>
      </tr>
    `);
    });

    // Calculate subtotal and grand total
    const subtotal = pos.items.reduce(
      (total, item) => total + (item.total || 0),
      0
    );
    const grandTotal = subtotal - (pos.discount || 0) + (pos.delivery_fee || 0);

    // Populate price section
    $("#pos-subtotal").text(formatPrice(subtotal || 0));
    $("#pos-discount").text(formatPrice(pos.discount || 0));
    $("#pos-delivery-fee").text(formatPrice(pos.delivery_fee || 0));
    $("#pos-total").text(formatPrice(grandTotal || 0));
  }
  // Initial fetch of orders and POS
  fetchOrdersAndPOS();
  // Handle Accept Order button click
  $("#accept-order-btn").on("click", function () {
    const orderRef = $(this).data("order-ref");

    // Check if order reference or rider ID is missing
    if (!orderRef || !riderId) {
      console.error("Order reference or rider ID is missing.");
      Swal.fire(
        "Error",
        "Unable to accept the order. Missing order reference or rider ID.",
        "error"
      );
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
      confirmButtonText: "Yes, accept it!",
    }).then((result) => {
      if (result.isConfirmed) {
        // AJAX request to update the order status to 'delivering'
        $.ajax({
          url: "/model-update-order", // Update with your backend URL
          type: "POST",
          data: {
            order_ref: orderRef,
            rider_id: riderId,
            status: "delivering",
          },
          success: function (response) {
            try {
              const jsonResponse =
                typeof response === "string" ? JSON.parse(response) : response;
              if (jsonResponse.success) {
                Swal.fire("Success", "Order accepted successfully!", "success");
                fetchOrdersAndPOS(); // Refresh the orders table
                $("#order-items-modal").modal("hide"); // Hide the modal
                fetchOrdersAndPOS(); // Refresh the accepted orders
              } else {
                Swal.fire(
                  "Error",
                  "Failed to accept the order: " + jsonResponse.message,
                  "error"
                );
                console.error("Order update failed:", jsonResponse.message);
              }
            } catch (e) {
              console.error("Error parsing server response:", e);
              Swal.fire(
                "Error",
                "An error occurred while accepting the order.",
                "error"
              );
            }
          },
          error: function (xhr, status, error) {
            console.error("Error accepting the order:", error);
            Swal.fire(
              "Error",
              "Failed to accept the order. Please try again.",
              "error"
            );
          },
        });
      }
    });
  });
  $("#accept-pos-btn").on("click", function () {
    const posRef = $(this).data("pos-ref");

    // Check if POS reference or rider ID is missing
    console.log("POS Ref:", posRef);
    console.log("Rider ID:", riderId);

    if (!posRef || !riderId) {
      console.error("POS reference or rider ID is missing.");
      Swal.fire(
        "Error",
        "Unable to accept the POS. Missing POS reference or rider ID.",
        "error"
      );
      return;
    }

    // Use SweetAlert to confirm the action
    Swal.fire({
      title: "Are you sure?",
      text: "Do you want to accept this POS?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, accept it!",
    }).then((result) => {
      if (result.isConfirmed) {
        // AJAX request to update the POS status to 'delivering'
        $.ajax({
          url: "/model-update-pos", // Update with your backend URL
          type: "POST",
          data: {
            pos_ref: posRef,
            rider_id: riderId,
            status: "delivering",
          },
          success: function (response) {
            try {
              const jsonResponse =
                typeof response === "string" ? JSON.parse(response) : response;
              if (jsonResponse.success) {
                Swal.fire("Success", "POS accepted successfully!", "success");
                fetchOrdersAndPOS(); // Refresh the POS table
                $("#pos-items-modal").modal("hide"); // Hide the modal
                fetchOrdersAndPOS(); // Refresh the accepted POS
              } else {
                Swal.fire(
                  "Error",
                  "Failed to accept the POS: " + jsonResponse.message,
                  "error"
                );
                console.error("POS update failed:", jsonResponse.message);
              }
            } catch (e) {
              console.error("Error parsing server response:", e);
              Swal.fire(
                "Error",
                "An error occurred while accepting the POS.",
                "error"
              );
            }
          },
          error: function (xhr, status, error) {
            console.error("Error accepting the POS:", error);
            Swal.fire(
              "Error",
              "Failed to accept the POS. Please try again.",
              "error"
            );
          },
        });
      }
    });
  });

  let acceptedOrders = []; // Global variable to store fetched accepted orders
  let acceptedPOS = []; // Global variable to store fetched accepted POS

  function fetchAcceptedOrdersAndPOS() {
    $.when(
      $.ajax({
        url: "/model-acceptedOrder", // URL for fetching accepted orders
        type: "GET",
        dataType: "json",
      }),
      $.ajax({
        url: "/model-acceptedPos", // URL for fetching accepted POS
        type: "GET",
        dataType: "json",
      })
    )
      .done(function (ordersResponse, posResponse) {
        console.log(ordersResponse, posResponse);
        // Filter and assign the response data to global variables
        acceptedOrders = ordersResponse[0].filter(
          (order) =>
            order.status === "delivering" && // Status is delivering
            order.rider_id == riderId // Matching rider ID
        );

        acceptedPOS = posResponse[0].filter(
          (posItem) =>
            posItem.status === "delivering" && // Status is delivering
            posItem.rider_id == riderId // Matching rider ID
        );

        displayAcceptedOrdersAndPOS(); // Display both accepted orders and POS
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.error(
          "Error fetching accepted orders or POS:",
          textStatus,
          errorThrown
        );
      });
  }

  function displayAcceptedOrdersAndPOS(orders = acceptedOrders, pos = acceptedPOS) {
    const container = $("#accepted-orders-container");
    container.empty(); // Clear the container before appending new orders and POS

    // Display Accepted Orders
    orders.forEach((order) => {
      const orderRef = order.order_ref || "N/A";
      const orderDate = formatDateTime(order.date) || "N/A";
      const paidStatus = `<span class="${getPaidStatusBadgeClass(
        order.paid
      )} me-2">${order.paid || "N/A"}</span>`;
      const deliveryStatus = `<span class="${getStatusBadgeClass(
        order.status
      )}">${order.status || "N/A"}</span>`;

      const orderCard = `
          <div class="card mb-3">
              <div class="card-body">
                  <div><strong>Order Ref:</strong> ${orderRef}</div>
                  <div><strong>Date:</strong> ${orderDate}</div>
                  <div class="d-flex mb-2">
                      ${paidStatus} 
                      ${deliveryStatus}
                  </div>
                  <div>
                      <button class="btn btn-primary view-order-btn" data-type="order" data-ref="${orderRef}">View Order</button>
                  </div>
              </div>
          </div>
          `;

      container.append(orderCard); // Append each accepted order card to the container
    });

    // Display Accepted POS
    pos.forEach((posItem) => {
      const posRef = posItem.pos_ref || "N/A";
      const posDate = formatDateTime(posItem.date) || "N/A";
      const paidStatus = `<span class="${getPaidStatusBadgeClass(
        posItem.paid
      )} me-2">${posItem.paid || "N/A"}</span>`;
      const deliveryStatus = `<span class="${getStatusBadgeClass(
        posItem.status
      )}">${posItem.status || "N/A"}</span>`;

      const posCard = `
          <div class="card mb-3">
              <div class="card-body">
                  <div><strong>POS Ref:</strong> ${posRef}</div>
                  <div><strong>Date:</strong> ${posDate}</div>
                  <div class="d-flex mb-2">
                      ${paidStatus} 
                      ${deliveryStatus}
                  </div>
                  <div>
                      <button class="btn btn-primary view-pos-btn" data-type="pos" data-ref="${posRef}">View POS</button>
                  </div>
              </div>
          </div>
          `;

      container.append(posCard); // Append each accepted POS card to the container
    });

    // Attach event listeners for both buttons
    attachAcceptedEventListeners();
  }

  // Search functionality: Filter orders and POS by their reference (order_ref or pos_ref)
  $("#search-input").on("input", function () {
    const searchTerm = $(this).val().toLowerCase();

    // Filter accepted orders by order_ref
    const searchedOrders = acceptedOrders.filter((order) =>
      order.order_ref.toLowerCase().includes(searchTerm)
    );

    // Filter accepted POS by pos_ref
    const searchedPOS = acceptedPOS.filter((posItem) =>
      posItem.pos_ref.toLowerCase().includes(searchTerm)
    );

    displayAcceptedOrdersAndPOS(searchedOrders, searchedPOS); // Display filtered orders and POS
  });

  // Sort functionality: Sort orders and POS based on selected criteria
  function sortAcceptedOrdersAndPOS(criteria) {
    const sortedOrders = [...acceptedOrders].sort((a, b) => {
      if (criteria === "Order Ref") {
        return a.order_ref.localeCompare(b.order_ref);
      } else if (criteria === "Date") {
        return new Date(b.date) - new Date(a.date); // Sort by newest date first
      } else if (criteria === "Paid") {
        return a.paid.localeCompare(b.paid);
      }
    });

    const sortedPOS = [...acceptedPOS].sort((a, b) => {
      if (criteria === "POS Ref") {
        return a.pos_ref.localeCompare(b.pos_ref);
      } else if (criteria === "Date") {
        return new Date(b.date) - new Date(a.date); // Sort by newest date first
      } else if (criteria === "Paid") {
        return a.paid.localeCompare(b.paid);
      }
    });

    displayAcceptedOrdersAndPOS(sortedOrders, sortedPOS); // Display sorted orders and POS
    attachAcceptedEventListeners();
  }

  // Sorting functionality: Trigger when a sort option is selected
  $(".dropdown-menu .dropdown-item").on("click", function (e) {
    e.preventDefault();
    const sortBy = $(this).data("sort"); // Get the data-sort attribute value
    sortAcceptedOrdersAndPOS(sortBy); // Sort orders and POS based on criteria
  });

  // Initial fetch
  fetchAcceptedOrdersAndPOS();


  function attachAcceptedEventListeners() {
    // Add event listeners for view buttons
    $("#accepted-orders-container").on("click", ".view-order-btn", function () {
      const orderRef = $(this).data("ref");
      // Handle view order action
      console.log(`Viewing order ${orderRef}`);
    });

    $("#accepted-orders-container").on("click", ".view-pos-btn", function () {
      const posRef = $(this).data("ref");
      // Handle view POS action
      console.log(`Viewing POS ${posRef}`);
    });
  }

  // Search functionality: Filter orders and POS by their reference (order_ref or pos_ref)
  $("#search-input").on("input", function () {
    const searchTerm = $(this).val().toLowerCase();

    // Filter accepted orders by order_ref
    const searchedOrders = acceptedOrders.filter((order) =>
      order.order_ref.toLowerCase().includes(searchTerm)
    );

    // Filter accepted POS by pos_ref
    const searchedPOS = acceptedPOS.filter((posItem) =>
      posItem.pos_ref.toLowerCase().includes(searchTerm)
    );

    displayAcceptedOrdersAndPOS(searchedOrders, searchedPOS); // Display filtered orders and POS
  });

  // Sort functionality: Sort orders and POS based on selected criteria
  function sortAcceptedOrdersAndPOS(criteria) {
    const sortedOrders = [...acceptedOrders].sort((a, b) => {
      if (criteria === "Order Ref") {
        return a.order_ref.localeCompare(b.order_ref);
      } else if (criteria === "Date") {
        return new Date(b.date) - new Date(a.date); // Sort by newest date first
      } else if (criteria === "Paid") {
        return a.paid.localeCompare(b.paid);
      }
    });

    const sortedPOS = [...acceptedPOS].sort((a, b) => {
      if (criteria === "POS Ref") {
        return a.pos_ref.localeCompare(b.pos_ref);
      } else if (criteria === "Date") {
        return new Date(b.date) - new Date(a.date); // Sort by newest date first
      } else if (criteria === "Paid") {
        return a.paid.localeCompare(b.paid);
      }
    });

    displayAcceptedOrdersAndPOS(sortedOrders, sortedPOS); // Display sorted orders and POS

  }

  // Sorting functionality: Trigger when a sort option is selected
  $(".dropdown-menu .dropdown-item").on("click", function (e) {
    e.preventDefault();
    const sortBy = $(this).data("sort"); // Get the data-sort attribute value
    sortAcceptedOrdersAndPOS(sortBy); // Sort orders and POS based on criteria
  });
  // Initial fetch
  fetchAcceptedOrdersAndPOS();
  // Function to attach event listeners
  function attachAcceptedEventListeners() {
    // Handle View button click for accepted orders
    $("#accepted-orders-container").on("click", ".view-order-btn", function () {
      const orderRef = $(this).data("ref");
      // Fetch and display accepted order details
      fetchAcceptedOrderDetails(orderRef);
    });

    // Handle View button click for accepted POS
    $("#accepted-orders-container").on("click", ".view-pos-btn", function () {
      const posRef = $(this).data("ref");
      $("#posPaymentButton").data("pos-ref", posRef);
      fetchAcceptedPOSDetails(posRef);
    });
  }

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

        // Clear existing order items
        const itemsContainer = $("#acceptedOrder-items-container");
        itemsContainer.empty();

        let subtotal = 0;

        // Generate and append items to the container, calculate subtotal
        data.items.forEach((item) => {
          const itemTotal = parseFloat(item.total_price) || 0;
          subtotal += itemTotal;

          const itemHtml = `
          <div class="d-flex justify-content-between pt-2">
            <div>
              <p class="mb-0"><strong>${item.product_name || "N/A"}</strong></p>
              <p class="mb-0">${formatPrice(item.unit_price)} (${item.variant_name || "N/A"
            }, ${item.unit_name || "N/A"}) x ${item.qty || 0}</p>
            </div>
            <p class="mb-0">${formatPrice(itemTotal)}</p>
          </div>`;
          itemsContainer.append(itemHtml);
        });

        // Populate other order details
        $("#acceptedOrder-items-modal-label").text(
          `Order: ${data.order_ref || "N/A"}`
        );
        $("#acceptedOrder-date").text(formatDateTime(data.date) || "N/A");
        $("#acceptedOrder-user-name").text(data.user_name || "N/A");
        $("#acceptedOrder-user-phone").text(data.user_phone || "N/A");

        // Format and display address
        const address =
          `${data.address.house_no ? data.address.house_no + ", " : ""}` +
          `${data.address.street ? data.address.street + ", " : ""}` +
          `${data.address.brgy ? data.address.brgy + ", " : ""}` +
          `${data.address.municipality ? data.address.municipality + "<br>" : ""
          }`;
        $("#acceptedOrder-address").html(address || "N/A");
        $("#acceptedOrder-address-desc").html(
          data.address.description || "N/A"
        );

        // Display subtotal (total of all items)
        $("#acceptedOrder-gross").text(formatPrice(subtotal));

        // Display delivery fee, VAT, discount, and grand total
        const deliveryFee = parseFloat(data.delivery_fee) || 0;
        const vat = parseFloat(data.vat) || 0;
        const discount = parseFloat(data.discount) || 0;
        const grandTotal = subtotal + deliveryFee + vat - discount;

        $("#acceptedOrder-delivery-fee").text(formatPrice(deliveryFee));
        $("#acceptedOrder-vat").text(formatPrice(vat));
        $("#acceptedOrder-discount").text(formatPrice(discount));
        $("#acceptedOrder-grand-total").text(formatPrice(grandTotal));

        // Show the modal
        $("#acceptedOrder-items-modal").modal("show");
      },
      error: function (xhr, status, error) {
        console.error("Error fetching accepted order details:", error);
      },
    });
  }
  function fetchAcceptedPOSDetails(posRef) {
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
        const items = data.items;
        console.log(items); // Log the items array to see what's inside
        // Clear existing POS items
        const itemsContainer = $("#acceptedPOS-items-container");
        itemsContainer.empty();

        let subtotal = 0;

        // Generate and append items to the container, calculate subtotal
        items.forEach((item) => {
          const itemTotal = parseFloat(item.total_price) || 0;
          subtotal += itemTotal;

          const itemHtml = `
          <div class="d-flex justify-content-between pt-2">
            <div>
              <p class="mb-0"><strong>${item.product_name || "N/A"}</strong></p>
              <p class="mb-0">${formatPrice(item.unit_price)} (${item.variant_name || "N/A"
            }, ${item.unit_name || "N/A"}) x ${item.qty || 0}</p>
            </div>
            <p class="mb-0">${formatPrice(itemTotal)}</p>
          </div>`;
          itemsContainer.append(itemHtml);
        });

        // Populate other POS details
        $("#acceptedPOS-items-modal-label").text(
          `POS: ${data.pos_ref || "N/A"}`
        );
        $("#acceptedPOS-date").text(formatDateTime(data.date) || "N/A");
        $("#acceptedPOS-user-name").text(data.user_name || "N/A");
        $("#acceptedPOS-user-phone").text(data.user_phone || "N/A");
        $("#acceptedPOS-address").text(data.address || "N/A");

        // Display subtotal (total of all items)
        $("#acceptedPOS-subtotal").text(formatPrice(subtotal));

        // Display delivery fee, discount, and grand total
        const deliveryFee = parseFloat(data.delivery_fee) || 0;
        const discount = parseFloat(data.discount) || 0;
        const grandTotal = subtotal + deliveryFee - discount;

        $("#acceptedPOS-delivery-fee").text(formatPrice(deliveryFee));
        $("#acceptedPOS-discount").text(formatPrice(discount));
        $("#acceptedPOS-grand-total").text(formatPrice(grandTotal));

        // Show the modal
        console.log("Opening the POS modal...");
        $("#acceptedPOS-items-modal").modal("show");
      },
      error: function (xhr, status, error) {
        console.error("Error fetching accepted POS details:", error);
      },
    });
  }

  $("#cancelOrderButton").on("click", function () {
    const orderRef = $("#acceptedOrder-items-modal-label")
      .text()
      .replace("Order: ", ""); // Get the order reference from the modal

    if (!orderRef) {
      console.error("Order reference is missing.");
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Unable to cancel the order. Order reference is missing.",
      });
      return;
    }

    // Show SweetAlert with a dropdown for cancellation reason
    Swal.fire({
      title: "Cancel Delivery",
      text: "Please select a reason for canceling this delivery.",
      icon: "warning",
      input: 'select',
      inputOptions: {
        'customer_request': 'Customer Request',
        'out_of_stock': 'Out of Stock',
        'incorrect_order': 'Incorrect Order',
        'payment_issue': 'Payment Issue',
        // Add more reasons as needed
      },
      inputPlaceholder: 'Select a cancellation reason',
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, cancel it!",
      preConfirm: (cancelReason) => {
        if (!cancelReason) {
          Swal.showValidationMessage("Please select a reason for cancellation.");
        }
        return cancelReason;
      }
    }).then((result) => {
      if (result.isConfirmed) {
        const cancelReason = result.value;

        // Proceed with AJAX request to cancel the order
        $.ajax({
          url: "/model-cancelOrder", // Update with the correct backend URL
          type: "POST",
          data: {
            order_ref: orderRef, // Send the order reference
            cancel_reason: cancelReason, // Send the selected reason
          },
          success: function (response) {
            try {
              const res =
                typeof response === "string" ? JSON.parse(response) : response;
              if (res.success) {
                Swal.fire({
                  icon: "success",
                  title: "Delivery Canceled",
                  text: "Delivery has been successfully canceled!",
                }).then(() => {
                  $("#acceptedOrder-items-modal").modal("hide"); // Close the modal
                  fetchAcceptedOrdersAndPOS(); // Refresh the accepted orders list
                });
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Cancellation Failed",
                  text: "Failed to cancel the order: " + res.message,
                });
                console.error("Order cancellation failed:", res.message);
              }
            } catch (e) {
              console.error("Error parsing server response:", e);
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "An error occurred while canceling the order.",
              });
            }
          },
          error: function (xhr, status, error) {
            console.error("Error canceling the order:", error);
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to cancel the order. Please try again.",
            });
          },
        });
      }
    });
  });
  $("#cancelPOSOrderButton").on("click", function () {
    const posRef = $("#acceptedPOS-items-modal-label")
      .text()
      .replace("POS: ", ""); // Get the POS reference from the modal

    // Check if POS reference is missing
    if (!posRef) {
      console.error("POS reference is missing.");
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Unable to cancel the Delivery. POS reference is missing.",
      });
      return;
    }

    // Show SweetAlert with a dropdown for cancellation reason
    Swal.fire({
      title: "Cancel Delivery",
      text: "Please select a reason for canceling this Delivery.",
      icon: "warning",
      input: 'select',
      inputOptions: {
        'out_of_stock': 'Out of Stock',
        'customer_request': 'Customer Request',
        'incorrect_order': 'Incorrect Order',
        'payment_issue': 'Payment Issue',
        // Add more reasons as needed
      },
      inputPlaceholder: 'Select a cancellation reason',
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, cancel it!",
      preConfirm: () => {
        const cancelReason = Swal.getInput().value; // Get selected value
        if (!cancelReason) {
          Swal.showValidationMessage("Please select a reason for cancellation.");
        }
        return cancelReason;
      }
    }).then((result) => {
      if (result.isConfirmed) {
        const cancelReason = result.value;

        // Proceed with AJAX request to cancel the POS order
        $.ajax({
          url: "/model-cancelPos", // Update with the correct backend URL
          type: "POST",
          data: {
            pos_ref: posRef, // Send the POS reference
            cancel_reason: cancelReason, // Send the selected reason
          },
          success: function (response) {
            try {
              const res = typeof response === "string" ? JSON.parse(response) : response;
              if (res.success) {
                Swal.fire({
                  icon: "success",
                  title: "Delivery Canceled",
                  text: "Delivery has been successfully canceled!",
                }).then(() => {
                  $("#acceptedPOS-items-modal").modal("hide"); // Close the modal
                  fetchAcceptedOrdersAndPOS(); // Refresh the accepted POS orders list
                });
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Cancellation Failed",
                  text: "Failed to cancel the POS order: " + res.message,
                });
                console.error("POS order cancellation failed:", res.message);
              }
            } catch (e) {
              console.error("Error parsing server response:", e);
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "An error occurred while canceling the POS order.",
              });
            }
          },
          error: function (xhr, status, error) {
            console.error("Error canceling the POS order:", error);
            Swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to cancel the POS order. Please try again.",
            });
          },
        });
      }
    });
  });
});
