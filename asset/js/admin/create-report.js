$(document).ready(function () {
    var today = new Date().toISOString().split('T')[0];
    $('#startDate, #endDate').attr('max', today);

    $('#create-report-form').submit(function (event) {
        event.preventDefault();

        $.ajax({
            url: '/create-report-content',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (json) {
                console.log(json);
                $('#content-thead').html(json.thead);
                $('#content-tbody').html(json.tbody);

                if ($('#content').hasClass('d-none')) {
                    $('#content').removeClass('d-none');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    });

    $('#print').click(function () {
        var module = $('#moduleSelect').val();
        var title;

        if (module === 'pos') {
            title = 'Sales Report';
        } else if (module === 'orders') {
            title = 'Order Report';
        }

        var content = $('#printContent').html();
        var header = `<div id="printHeader">
                        <div class="text-center" style="font-size: 12px;">
                            <h5 class="mb-0 fw-semibold" style="font-size: 14px;">FM Odulio's Enterprise & Gen. Merchandise</h5>
                            <p class="mb-0" style="font-size: 10px;">Mc Arthur Hi-way, Poblacion II, Marilao, Bulacan</p>
                            <p class="mb-0" style="font-size: 10px;">fmoduliogenmdse@yahoo.com</p>
                            <p class="mb-0" style="font-size: 10px;">0922-803-3898</p>
                            <h6 class="mt-2 mb-2" style="font-size: 12px;">` + title + `</h6>
                        </div>
                        <hr style="margin: 5px 0;">
                    </div>`;

        // Append the header to the content
        $('body').html(header + content);

        // Print the page
        window.print();

        // Restore the original content
        location.reload();
    });

    $('#dateRange').on('change', function () {
        let today = new Date();
        let startDate = new Date();
        let endDate = new Date(today);

        let rangeType = $(this).val();
        if (rangeType === 'daily') {
            startDate = endDate; // Start and End date are the same for daily
        } else if (rangeType === 'weekly') {
            startDate.setDate(today.getDate() - 6); // Last 7 days
        } else if (rangeType === 'monthly') {
            startDate.setMonth(today.getMonth() - 1); // Previous month
        } else if (rangeType === 'annually') {
            startDate.setFullYear(today.getFullYear() - 1); // Previous year
        }

        // Format the date to 'YYYY-MM-DD' for the input fields
        let formatDate = function (date) {
            let d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        };

        // Auto-fill the start and end date inputs
        $('#startDate').val(formatDate(startDate));
        $('#endDate').val(formatDate(endDate));
    });
})