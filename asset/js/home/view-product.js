function buy_now(product_id) {
    $.ajax({
        url: '/buy-now',
        method: 'POST',
        data: {
            product_id: product_id
        },
        dataType: 'json',
        success: function (json) {

        }
    });
}
$(document).ready(function () {
    $('.add-to-cart-btn').on('click', function () {
        $('#product-modal').modal('show');
    });

    $('.buy-now-btn').click(function () {
        $('#buy-now-modal').modal('show');
    })

    $('#buy-now-form').on('submit', function (event) {
        event.preventDefault();
        let serializedData = $(this).serialize();
        let formDataArray = $(this).serializeArray();
        let product_id = formDataArray.find(item => item.name === 'product_id').value;

        console.log(product_id)

        $.ajax({
            url: '/add-cart',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (feedback) {
                buy_now(product_id);
                window.location.href = '/cart';
            }, error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });

    $('#add-to-cart-form').on('submit', function (event) {
        event.preventDefault();

        $.ajax({
            url: '/add-cart',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (feedback) {
                if (feedback.product_exist) {
                    $.notify(feedback.product_exist, 'error');
                }

                if (feedback.cart_count) {
                    $('#cart-count').text(feedback.cart_count);
                }

                if (feedback.product_added) {
                    $.notify(feedback.product_added, 'success');
                }
            }, error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });
})