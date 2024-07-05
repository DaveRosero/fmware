$(document).ready(function () {
  $(".view-history-btn").click(function () {
    const posRef = $(this).data("bs-posref");

    $.ajax({
      url: "/pos-getTransaction",
      method: "GET",
      data: {
        pos_ref: posRef,
      },
      dataType: "json",
      success: function (data) {
        console.log(data);
        $("#historyViewLabel").text("Transaction #" + data.pos_ref);
        $("#viewfName-input").val(data.firstname);
        $("#viewlName-input").val(data.lastname);
        $("#transaction-date").text(data.date);
        $("#transaction-subtotal").text(data.subtotal);
        $("#htransaction-total").text("₱" + data.total);
        $("#transaction-discount").text(data.discount);
        $("#viewcashRec-input").val(data.cash);
        $("#history-change").text(data.changes);
        $("#transaction-delivery-fee").text(data.delivery_fee);
        $("#transaction-deliverer").text(data.deliverer_name);
        $("#transaction-contact").text(data.contact_no);
        $("#transaction-transaction-type").text(data.transaction_type);
        $("#paymentMethod").val(data.payment_type);
        $("#history-username").text(data.username);
        $("#transaction-status").text(data.status);
        $("#historyView").modal("show");

        $.ajax({
          url: "/pos-historyprod",
          method: "GET",
          data: {
            pos_ref: posRef,
          },
          dataType: "html",
          success: function (data) {
            console.log(data);
            $("#productDetails").html(data);
          },
        });
      },
    });
  });
});
