$(document).ready(function () {
    var today = new Date();
    var localToday = today.getFullYear() + '-' +
        ('0' + (today.getMonth() + 1)).slice(-2) + '-' +
        ('0' + today.getDate()).slice(-2);
    $('#startDate, #endDate').attr('max', localToday);

    // Prevent user from editing the dates on load
    $('#startDate, #endDate').prop('readonly', true); // Make dates readonly by default

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
        var dateRange = $('#dateRange').val();
        var title;

        // Set dynamic title based on module and date range
        if (module === 'pos') {
            if (dateRange === 'daily') {
                title = 'Daily Point of Sales Report';
            } else if (dateRange === 'weekly') {
                title = 'Weekly Point of Sales Report';
            } else if (dateRange === 'monthly') {
                title = 'Monthly Point of Sales Report';
            } else if (dateRange === 'annually') {
                title = 'Annual Point of Sales Report';
            } else if (dateRange === 'custom') {
                // Custom Date Range Title
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();

                var formattedStartDate = new Date(startDate);
                var formattedEndDate = new Date(endDate);

                // Format the dates to a readable format (e.g., "November 12 to 14")
                var options = { month: 'long', day: 'numeric' };
                var formattedStart = formattedStartDate.toLocaleDateString('en-US', options);
                var formattedEnd = formattedEndDate.toLocaleDateString('en-US', options);

                title = `Point of Sales Report from ${formattedStart} to ${formattedEnd}`;
            }
        } else if (module === 'orders') {
            if (dateRange === 'daily') {
                title = 'Daily Online Order Report';
            } else if (dateRange === 'weekly') {
                title = 'Weekly Online Order Report';
            } else if (dateRange === 'monthly') {
                title = 'Monthly Online Order Report';
            } else if (dateRange === 'annually') {
                title = 'Annual Online Order Report';
            } else if (dateRange === 'custom') {
                // Custom Date Range Title
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();

                var formattedStartDate = new Date(startDate);
                var formattedEndDate = new Date(endDate);

                // Format the dates to a readable format (e.g., "November 12 to 14")
                var options = { month: 'long', day: 'numeric' };
                var formattedStart = formattedStartDate.toLocaleDateString('en-US', options);
                var formattedEnd = formattedEndDate.toLocaleDateString('en-US', options);

                title = `Online Order Report from ${formattedStart} to ${formattedEnd}`;
            }
        }

        // Get the current date and time
        var currentDate = new Date();
        var formattedDate = currentDate.toLocaleDateString() + ' ' + currentDate.toLocaleTimeString();

        // Get the user name from the hidden input field
        var userName = $('#user').val();

        var content = $('#printContent').html();
        var header = `<div id="printHeader">
                        <div class="text-center" style="font-size: 12px;">
                            <h5 class="mb-0 fw-semibold" style="font-size: 14px;">FM Odulio's Enterprise & Gen. Merchandise</h5>
                            <p class="mb-0" style="font-size: 10px;">Mc Arthur Hi-way, Poblacion II, Marilao, Bulacan</p>
                            <p class="mb-0" style="font-size: 10px;">fmoduliogenmdse@yahoo.com</p>
                            <p class="mb-0" style="font-size: 10px;">0922-803-3898</p>
                            <h6 class="mt-2" style="font-size: 12px;">` + title + `</h6>
                            <p style="font-size: 10px;">Printed on: ` + formattedDate + ` | Printed by: ` + userName + `</p>
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

        // Enable or disable the date inputs based on the selected range
        if (rangeType === 'custom') {
            $('#startDate, #endDate').prop('readonly', false);  // Enable manual input for custom date range
        } else {
            $('#startDate, #endDate').prop('readonly', true);   // Disable manual input for other date ranges
        }

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
});