$(document).ready(function () {
  $(".view-button").on("click", function () {
    var pos_ref = $(this).data("product-id");

    $.ajax({
      url: "/pos-history",
      method: "GET",
      data: { pos_ref: pos_ref },
      dataType: "json",
      success: function (json) {
        $("#historyViewLabel").text("Transaction #" + json.pos_ref);

        var html = "";
        $.each(json.products, function (index, product) {
          html += "<tr>";
          html += '<td class="align-middle">' + product.name + "</td>";
          html +=
            '<td class="align-middle">' +
            product.unit_value +
            " " +
            product.unitname.toUpperCase() +
            "</td>";
          html += '<td class="align-middle">' + product.variant + "</td>";
          html += '<td class="align-middle">' + product.qty + "</td>";
          html += "</tr>";
        });
        $("#productDetails").html(html);
        $("#transaction-total").text("$" + json.total);
        $("#paymentMethod").val(json.payment_method).prop("disabled", true);
        $("#viewcashRec-input").val(json.cash_received).prop("disabled", true);
        $("#transactionType").val(json.transaction_type).prop("disabled", true);
        $("#viewfName-input")
          .val(json.customer.first_name)
          .prop("disabled", true);
        $("#viewlName-input")
          .val(json.customer.last_name)
          .prop("disabled", true);
        $("#viewcontact-input")
          .val(json.customer.contact)
          .prop("disabled", true);
        $("#fulfilledBy").text(json.fulfilled_by);

        $("#historyView").modal("show");
      },
      error: function () {
        console.error("Failed to fetch product details");
      },
    });
  });
});
