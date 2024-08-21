$.fn.dataTable.moment('MMMM D, YYYY');

function viewRefund(refund_id) {
    $.ajax({
        url: '/view-refund',
        method: 'POST',
        data: {
            refund_id: refund_id
        },
        dataType: 'json',
        success: function (json) {
            $('#viewModalLabel').text(json.title);
            $('#view-content').html(json.content);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("Error:", textStatus, errorThrown);
            console.log("Response:", jqXHR.responseText);
        }
    })
}

function viewReplacement(replacement_id) {
    $.ajax({
        url: '/view-replacement',
        method: 'POST',
        data: {
            replacement_id: replacement_id
        },
        dataType: 'json',
        success: function (json) {
            $('#viewModalLabel').text(json.title);
            $('#view-content').html(json.content);
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

    $(document).on('click', '.view-refund', function () {
        console.log($(this).data('refund-id'));
        viewRefund($(this).data('refund-id'));
    });

    $(document).on('click', '.view-replacement', function () {
        console.log($(this).data('replacement-id'));
        viewReplacement($(this).data('replacement-id'));
    });
})