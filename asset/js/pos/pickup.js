$(document).ready(function () {
  // To sort the pickup order based on the transaction date
  var pickupTable = $("#pickup-search").DataTable({
    order: [[1, "desc"]],
    stateSave: true,
  });

  // Reset DataTable when the modal is closed
  $("#pickup-searchModal").on("hidden.bs.modal", function () {
    pickupTable.order([[1, "desc"]]).draw(); // Reset sorting order
    pickupTable.search("").draw(); // Clear search filter
  });

  //view button to show the modal and detail based on the order_ref
  $;
});
