$(document).ready(function () {
  function updateCartUI(response) {
    $("#cart-body").html(response.tbody);
    $("#cart-total").text("Subtotal: ₱" + response.cart_total);
    $("#cart-total-modal").text("₱" + response.cart_total);
    $("#cart-subtotal-modal").text("₱" + response.cart_total);
    $("#cart-body-modal").html(response.tbody_modal);

    // Check if cart is empty and enable/disable checkout button
    if ($("#cart-body").children().length === 0) {
      $("#checkout-button").prop("disabled", true);
    } else {
      $("#checkout-button").prop("disabled", false);
    }
  }

  function getPOS() {
    $.ajax({
      url: "/pos-getpos",
      method: "POST",
      dataType: "json",
      success: function (response) {
        console.log("AJAX call successful");
        console.log("Response:", response);
        updateCartUI(response);
      },
      error: function (xhr, status, error) {
        console.error("AJAX call failed");
        console.error("Status:", status);
        console.error("Error:", error);
      },
    });
  }

  function addPOS(id, price) {
    $.ajax({
      url: "/pos-addpos",
      method: "POST",
      data: {
        product_id: id,
        price: price,
      },
      dataType: "text",
      success: function () {
        getPOS();
      },
      error: function (xhr, status, error) {
        console.error("AJAX call failed");
        console.error("Status:", status);
        console.error("Error:", error);
      },
    });
  }

  function updateQty(id, qty) {
    $.ajax({
      url: "/pos-change",
      method: "POST",
      data: {
        id: id,
        qty: qty,
      },
      dataType: "json",
      success: function (response) {
        updateCartUI(response);
      },
      error: function (xhr, status, error) {
        console.error("AJAX call failed");
        console.error("Status:", status);
        console.error("Error:", error);
      },
    });
  }

  getPOS();

  function handleSearch() {
    var searchValue = $("#barcode").val().trim(); // Trim whitespace

    if (searchValue === "") {
      // If search value is empty, fetch all products
      fetchAllProducts();
    } else {
      // Perform AJAX call to fetch products based on searchValue
      $.ajax({
        url: "/pos-search",
        method: "POST",
        data: { search: searchValue },
        dataType: "html",
        success: function (response) {
          // Update the product list with the search results
          $(".item-list").html(response);
        },
        error: function (xhr, status, error) {
          console.error("AJAX call failed");
          console.error("Status:", status);
          console.error("Error:", error);
        },
      });
    }
  }

  function fetchAllProducts() {
    // Perform AJAX call to fetch all products
    $.ajax({
      url: "/pos-fetchall",
      method: "POST",
      dataType: "html",
      success: function (response) {
        // Update the product list with all products
        $(".item-list").html(response);
      },
      error: function (xhr, status, error) {
        console.error("AJAX call failed");
        console.error("Status:", status);
        console.error("Error:", error);
      },
    });
  }

  fetchAllProducts();

  let debounceTimeout;
  const debounceDelay = 100; // Adjust as needed

  $("#barcode").on("input", function () {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(function () {
      handleSearch();
    }, debounceDelay);
  });
  $("#barcode").on("keydown", function (event) {
    if (event.key === "Enter") {
      event.preventDefault(); // Prevent default form submission

      var barcodeInput = $("#barcode").val().trim();

      // Remove prefix (e.g., 'FM') if it exists
      var prefix = "FM";
      if (barcodeInput.startsWith(prefix)) {
        barcodeInput = barcodeInput.slice(prefix.length); // Remove prefix
      }

      console.log("Processed Barcode:", barcodeInput); // Debugging line

      $.ajax({
        url: "/pos-barcode",
        method: "POST",
        data: $("#barcode-form").serialize(),
        dataType: "json",
        success: function (response) {
          addPOS(response.id, response.price);
          $("#barcode").val(""); // Clear the input field
          setTimeout(function () {
            $("#barcode").focus(); // Set focus back to the input field
          }, 100); // Slight delay to ensure the focus is properly set
        },
        error: function (xhr, status, error) {
          console.error("AJAX call failed");
          console.error("Status:", status);
          console.error("Error:", error);
        },
      });
      return false;
    }
  });

  $("#barcode-form").on("submit", function (event) {
    event.preventDefault();
    var barcodeInput = $("#barcode").val().trim();

    if (barcodeInput !== "") {
      $.ajax({
        url: "/pos-barcode",
        method: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function (json) {
          addPOS(json.id, json.price);
          $("#barcode").val("").focus();
        },
        error: function (xhr, status, error) {
          console.error("AJAX call failed");
          console.error("Status:", status);
          console.error("Error:", error);
        },
      });
    }
  });

  $(document).on("click", ".cart-delete", function () {
    var id = $(this).data("product-id");
    var row = $(this).closest("tr");

    row.remove();

    $.ajax({
      url: "/pos-removecart",
      method: "POST",
      data: { id: id },
      dataType: "json",
      success: function (response) {
        updateCartUI(response);
      },
      error: function (xhr, status, error) {
        console.error("AJAX call failed");
        console.error("Status:", status);
        console.error("Error:", error);
      },
    });
  });

  $(document).on("click", ".add-qty", function () {
    var id = $(this).data("product-id");
    var qty = $(this).data("product-qty");

    $.ajax({
      url: "/pos-addqty",
      method: "POST",
      data: {
        id: id,
        qty: qty,
      },
      dataType: "json",
      success: function (response) {
        updateCartUI(response);
      },
      error: function (xhr, status, error) {
        console.error("AJAX call failed");
        console.error("Status:", status);
        console.error("Error:", error);
      },
    });
  });

  $(document).on("click", ".minus-qty", function () {
    var id = $(this).data("product-id");
    var qty = $(this).data("product-qty");

    $.ajax({
      url: "/pos-minusqty",
      method: "POST",
      data: {
        id: id,
        qty: qty,
      },
      dataType: "json",
      success: function (response) {
        updateCartUI(response);
      },
      error: function (xhr, status, error) {
        console.error("AJAX call failed");
        console.error("Status:", status);
        console.error("Error:", error);
      },
    });
  });

  $(document).on("change", ".qty-input", function () {
    var id = $(this).closest("tr").find(".cart-delete").data("product-id");
    var qty = $(this).val();

    updateQty(id, qty);
  });

  $(document).on("click", ".cart-button", function () {
    var id = $(this).data("product-id");
    var price = $(this).data("product-price");
    $.ajax({
      url: "/pos-cart",
      method: "POST",
      data: {
        id: id,
        price: price,
      },
      dataType: "json",
      success: function (response) {
        console.log("AJAX call successful");
        console.log("Response:", response);
        updateCartUI(response);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log("Error:", textStatus, errorThrown);
        console.log("Response:", jqXHR.responseText);
      },
    });
  });

  $(".reset-cart").on("click", function () {
    $.ajax({
      url: "/pos-reset",
      success: function (response) {
        $("#cart-body").empty();
        $("#cart-body-modal").empty();
        $("#cart-total-modal").empty();
        $("#cart-subtotal-modal").empty();
        $("#cart-total").text("Subtotal: ₱0.00");
        $("#cart-total-modal").text("₱0.00");
        $("#cart-subtotal-modal").text("₱0.00");
        console.log("Cart reset successful");
        $("#checkout-button").prop("disabled", true);
      },
      error: function (xhr, status, error) {
        console.error("AJAX call failed");
        console.error("Status:", status);
        console.error("Error:", error);
      },
    });
  });
});
