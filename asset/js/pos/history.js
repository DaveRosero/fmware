$(document).ready(function() {
    $(".view-button").on("click", function() {
        var posRef = $(this).data("product-id");

        // Update the modal's title with the transaction number
        $("#historyViewLabel").text("Transaction #" + posRef);

        // Fetch the product details and update the modal body
        $.ajax({
            url: "/pos-history",
            method: "GET",
            data: { pos_ref: posRef },
            success: function(data) {
                $("#historyView tbody").html(data);
            },
            error: function() {
                console.error("Failed to fetch product details");
            }
        });
    });
});

