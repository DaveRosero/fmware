$.fn.dataTable.moment('MMMM D, YYYY');

function viewRefund(refund_id, type) {
    $.ajax({
        url: '/view-refund',
        method: 'POST',
        data: {
            refund_id: refund_id,
            type: type
        },
        dataType: 'json',
        success: function (json) {
            $('#viewModalLabel').text(json.title);
            $('#view-content').html(json.content);
            $('#staff').text(json.staff);
            $('#customer').text(json.customer);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("Error:", textStatus, errorThrown);
            console.log("Response:", jqXHR.responseText);
        }
    })
}

function viewReplacement(replacement_id, type) {
    $.ajax({
        url: '/view-replacement',
        method: 'POST',
        data: {
            replacement_id: replacement_id,
            type: type
        },
        dataType: 'json',
        success: function (json) {
            console.log(json.test);
            $('#viewModalLabel').text(json.title);
            $('#view-content').html(json.content);
            $('#staff').text(json.staff);
            $('#customer').text(json.customer);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("Error:", textStatus, errorThrown);
            console.log("Response:", jqXHR.responseText);
        }
    })
}

$(document).ready(function () {
    $('#returns-table').DataTable({
        order: [
            [2, 'desc']
        ]
    })

    $('#returns-table').on('click', '.view-refund', function () {
        console.log($(this).data('refund-id') + $(this).data('type'));
        viewRefund($(this).data('refund-id'), $(this).data('type'));
    });

    $('#returns-table').on('click', '.view-replacement', function () {
        console.log($(this).data('replacement-id') + $(this).data('type'));
        viewReplacement($(this).data('replacement-id'), $(this).data('type'));
    });
})