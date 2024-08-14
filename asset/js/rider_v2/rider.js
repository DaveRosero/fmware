$(document).ready(function () {
  let riderId = null; // Declare a global variable

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

  function fetchOrders() {
    $.ajax({
      url: "/model-order",
      type: "GET",
      dataType: "json",
      success: function (data) {
        const filteredOrders = data.filter(order =>
          order.status.toLowerCase() === 'pending' &&
          (!order.rider_id || order.rider_id === null)
        );
        // Initialize DataTable
        $("#orders-table").DataTable({
          data: filteredOrders.map((order) => [
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
        $("#order-address-desc").html(data.address.description || "N/A");

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
    $("#accept-order-btn").data("order-ref", orderRef);
    fetchOrderDetails(orderRef);
  });

  $("#accept-order-btn").on("click", function () {
    var orderRef = $(this).data("order-ref");
    // Debugging: Log the data being sent
    console.log("Data being sent:", {
      order_ref: orderRef,
      rider_id: riderId,
      status: "delivering"
    });

    if (!orderRef || !riderId) {
      console.error("Order reference or rider ID is missing.");
      return;
    }

    // Send AJAX request to update the order
    $.ajax({
      url: "/model-update-order", // Update this URL to your PHP script
      type: "POST",
      data: {
        order_ref: orderRef,
        rider_id: riderId,
        status: "delivering"
      },
      success: function (response) {
        console.log("Server response:", response);
        try {
          // Parse the response if it’s a string
          const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
      
          if (jsonResponse.success) {
            fetchOrders(); // Refresh the orders table
            $("#order-items-modal").modal("hide"); // Hide the modal
          } else {
            console.error("Order update failed:", jsonResponse.message);
          }
        } catch (e) {
          console.error("Error parsing server response:", e);
        }
      }
    });
  });

  // QR Code Scanner Modal integration
  const video = document.getElementById("video");
  const scannedImage = document.getElementById("scannedImage");
  const qrDataLabel = document.getElementById("qrData");
  const webcamButton = document.getElementById("webcamButton");
  const fileInput = document.getElementById("fileInput");
  const fileInputButton = document.getElementById("fileInputButton");

  function showWebcam() {
    qrDataLabel.textContent = "";
    qrDataLabel.style.display = "none";
    scannedImage.style.display = "none";
    video.style.display = "block";
    startWebcamScanning();
  }

  function startWebcamScanning() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices
        .getUserMedia({ video: { facingMode: "environment" } })
        .then(function (stream) {
          video.srcObject = stream;
          video.play();

          // Adjust the canvas size once the video metadata is loaded
          video.onloadedmetadata = function () {
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");

            // Continuously check for QR code in each frame
            const intervalId = setInterval(() => {
              if (video.videoWidth > 0 && video.videoHeight > 0) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = ctx.getImageData(
                  0,
                  0,
                  canvas.width,
                  canvas.height
                );
                const code = jsQR(
                  imageData.data,
                  imageData.width,
                  imageData.height
                );

                if (code) {
                  const qrData = code.data;
                  qrDataLabel.textContent = "QR Code Data: " + qrData;
                  qrDataLabel.style.display = "block";
                  clearInterval(intervalId);
                  const tracks = video.srcObject.getTracks();
                  tracks.forEach((track) => track.stop());
                  scannedImage.src = canvas.toDataURL();
                  scannedImage.style.display = "block";
                  video.style.display = "none";

                  if (isUrl(qrData)) {
                    window.location.href = qrData;
                  }
                }
              }
            }, 100); // Adjust the interval as needed (milliseconds)
          };
        })
        .catch(function (error) {
          qrDataLabel.textContent = "Error accessing camera: " + error;
        });
    } else {
      qrDataLabel.textContent = "getUserMedia is not supported";
    }
  }

  function stopVideoStream() {
    const stream = video.srcObject;
    if (stream) {
      const tracks = stream.getTracks();
      tracks.forEach((track) => track.stop());
      video.srcObject = null;
    }
  }

  function isUrl(str) {
    return str.startsWith("http://") || str.startsWith("https://");
  }

  fileInput.addEventListener("change", function (event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function (event) {
      const image = new Image();
      image.onload = function () {
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        canvas.width = image.width;
        canvas.height = image.height;
        ctx.drawImage(image, 0, 0);
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code) {
          qrDataLabel.textContent = "QR Code Data: " + code.data;
          qrDataLabel.style.display = "block";
          scannedImage.src = event.target.result;
          scannedImage.style.display = "block";
          video.style.display = "none";

          if (isUrl(code.data)) {
            window.location.href = code.data;

          }
        } else {
          qrDataLabel.textContent = "No QR code found in the selected image";
          qrDataLabel.style.display = "block";
        }
      };
      image.src = event.target.result;
    };
    reader.readAsDataURL(file);
  });

  // Add event listener to file input button to trigger file input click
  fileInputButton.addEventListener("click", function () {
    fileInput.click();
  });

  webcamButton.addEventListener("click", function () {
    $("#qr-scanner-modal").modal("show");
    showWebcam();
  });

  // Stop video stream when QR Scanner Modal is hidden
  $("#qr-scanner-modal").on("hidden.bs.modal", function () {
    stopVideoStream();
  });
});
