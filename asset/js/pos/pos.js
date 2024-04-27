var logo = new Image();
var logosrc = "asset/images/store/logo.png";
logo.src = logosrc;

//Printing Functionality
// Define a function to generate printable receipt content
function generatePrintableContent() {
  var content = "";
  var total = $("#cart-total").text();
  var salesReceiptNumber = "FMx0001";
  var customerName = "Customer Name";
  var vat = 0.12;
  var expiryDate = "03-31-24";

  $("#cart-body tr").each(function () {
    var name = $(this).find("td:eq(0)").text();
    var qty = $(this).find(".qty-input").val();
    var price = $(this).find("td:eq(4)").text();
    var discount = $(this).find(".discount").val();

    content +=
      '<tr><td class="center">' +
      name +
      '</td><td class="center">' +
      qty +
      '</td><td class="center">' +
      price +
      '</td><td class="center">' +
      discount +
      "</td></tr>";
  });

  var printableContent =
    "<style>" +
    "table { width: 100%; border-collapse: collapse; }" +
    "th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }" +
    "th { background-color: #f2f2f2; }" +
    ".receipt { margin: 20px auto; max-width: 400px; page-break-after: always; }" +
    ".total { font-weight: bold; text-align: right; margin-top: 10px; }" +
    ".header { text-align: center; margin-bottom: 20px; }" +
    ".logo { width: 100px; height: 100px; margin-bottom: 10px; }" +
    ".center { text-align: center; }" +
    ".signature { margin-top: 30px; }" +
    ".signature p { text-align: center; margin-top: 50px; }" +
    ".vat { text-align: right; }" +
    ".discount { text-align: right; }" +
    "</style>" +
    '<div class="receipt">' +
    '<div class="header">' +
    '<img src="' +
    logosrc +
    '" alt="Company Logo" class="logo">' +
    "<h1>F.M. ODULIOS ENTERPRISES AND GEN. MERCHANDISE</h1>" +
    "<p>Mc Arthur HI-way, Poblacion II, Marilao, Bulacan</p>" +
    "</div>" +
    "<h2>Sales Receipt - Original Copy</h2>" +
    "<p>Sales Receipt Number: " +
    salesReceiptNumber +
    "</p>" +
    "<p>Customer Name: " +
    customerName +
    "</p>" +
    "<table>" +
    "<thead><tr><th>Item</th><th>Quantity</th><th>Price</th><th>Discount</th></tr></thead>" +
    "<tbody>" +
    content +
    "</tbody>" +
    "</table>" +
    '<div class="total">' +
    total +
    "</span></div>" +
    '<div class="signature">' +
    "<p>Staff Signature:__________</p>" +
    "<p>Customer Signature:__________</p>" +
    "</div>" +
    "</div>" +

    '<div class="receipt">' +
    '<div class="header">' +
    '<img src="' +
    logosrc +
    '" alt="Company Logo" class="logo">' +
    "<h1>F.M. ODULIOS ENTERPRISES AND GEN. MERCHANDISE</h1>" +
    "<p>Mc Arthur HI-way, Poblacion II, Marilao, Bulacan</p>" +
    "</div>" +
    "<h2>Sales Receipt - Customer Copy</h2>" +
    "<p>Sales Receipt Number: " +
    salesReceiptNumber +
    "</p>" +
    "<p>Customer Name: " +
    customerName +
    "</p>" +
    "<table>" +
    "<thead><tr><th>Item</th><th>Quantity</th><th>Price</th><th>Discount</th></tr></thead>" +
    "<tbody>" +
    content +
    "</tbody>" +
    "</table>" +
    '<div class="total">' +
    total +
    "</span></div>" +
    '<div class="signature">' +
    "<p>Staff Signature:__________</p>" +
    "<p>Customer Signature:__________</p>" +
    "</div>" +
    "</div>";

  return printableContent;
}

//Printing Functionality
$(".print").on("click", function () {
  function printReceiptWithLogo() {
    var printableContent = generatePrintableContent();

    var printWindow = window.open("", "_blank");
    printWindow.document.write(
      "<html><head><title>Receipt</title></head><body>" +
      printableContent +
      "</body></html>"
    );
    printWindow.document.close();

    printWindow.print();

    window.onafterprint = function () {
      printWindow.close();
    };
  }

  if (logo.complete) {
    // If logo is already loaded, print receipt immediately
    printReceiptWithLogo();
  } else {
    // If logo is not yet loaded, wait for it to load before printing receipt
    logo.onload = printReceiptWithLogo;
  }
});

//Barcode Functionality
$(document).ready(function () {
  function getPOS() {
    $.ajax({
      url: '/pos-getpos',
      method: 'POST',
      dataType: 'json',
      success: function (response) {
        console.log("AJAX call successful");
        console.log("Response:", response);
        $("#cart-body").html(response.tbody);
        $("#cart-total").text("Total: ₱" + response.cart_total);
      }
    })
  }

  function addPOS(id, price) {
    $.ajax({
      url: '/pos-addpos',
      method: 'POST',
      data: {
        product_id: id,
        price: price
      },
      dataType: 'text',
      success: function () {
        getPOS();
      }
    })
  }

  getPOS();

  $('#barcode-form').on('submit', function (event) {
    event.preventDefault();
    console.log($(this).serialize());
    var barcodeInput = $('#barcode');

    $.ajax({
      url: '/pos-barcode',
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (json) {
        console.log(json.id);
        console.log(json.price);
        addPOS(json.id, json.price);
        getPOS();
        $('#barcode').val('');
        barcodeInput.focus();
      }
    })
  })
})



//Document Ready Functionality
$(document).ready(function () {
  $(document).on("click", ".cart-delete", function () {
    var id = $(this).data("product-id");
    var row = $(this).closest("tr");

    row.remove();

    $.ajax({
      url: "/pos-removecart",
      method: "POST",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (response) {
        $("#cart-body").html(response.tbody);
        $("#cart-total").text("Total: ₱" + response.cart_total);
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
        $("#cart-body").html(response.tbody);
        $("#cart-total").text("Total: ₱" + response.cart_total);
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
        $("#cart-body").html(response.tbody);
        $("#cart-total").text("Total: ₱" + response.cart_total);
      },
    });
  });

  $(".cart-button").on("click", function () {
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
        $("#cart-body").html(response.tbody);
        $("#cart-total").text("Total: ₱" + response.cart_total);
      },
      error: function (xhr, status, error) {
        console.log("AJAX call failed");
        console.log("Status:", status);
        console.log("Error:", error);
      },
    });
  });

  $(document).on("click", ".apply-discount", function () {
    var row = $(this).closest("td");
    var discount = row.find(".discount");
    var value = discount.val();
    var id = $(this).data("product-id");

    $.ajax({
      url: "/pos-ctdiscount",
      method: "POST",
      data: {
        id: id,
        discount: value,
      },
      dataType: "json",
      success: function (response) {
        $("#cart-body").html(response.tbody);
        $("#cart-total").text("Total: ₱" + response.cart_total);
      },
    });
  });
  $(".reset-cart").on("click", function () {
    $.ajax({
      url: "/pos-reset",
      success: function () {
        $("#cart-body").empty();
        $("#cart-total").empty();
        $("#cart-total").text("Total: ₱0");
      },
    });
  });
});
