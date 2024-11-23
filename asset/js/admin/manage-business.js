$(document).ready(function () {
    JsBarcode(".barcode").init();

    function updateDeliveryFeeStatus(active, id) {
        $.ajax({
            url: '/update-df-status',
            method: 'POST',
            data: {
                active: active,
                id: id
            },
            dataType: 'json',
            success: function (json) {
                if (json.redirect) {
                    window.location.href = json.redirect;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    }

    function delExpense(id) {
        $.ajax({
            url: '/delete-expenses',
            method: 'POST',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (json) {
                if (json.redirect) {
                    window.location.href = json.redirect
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    }

    function showChangePinAlert() {
        Swal.fire({
            title: 'Change Your PIN',
            html: `
                <div class="form-group mb-3">
                    <label for="old-pin-input" class="form-label">Current PIN</label>
                    <input type="password" id="old-pin-input" class="form-control" placeholder="Enter Current PIN" maxlength="4">
                </div>
                <div class="form-group mb-3">
                    <label for="new-pin-input" class="form-label">New PIN</label>
                    <input type="password" id="new-pin-input" class="form-control" placeholder="Enter New PIN" maxlength="4">
                </div>
                <button id="forgot-pin" class="btn btn-link p-0">Forgot PIN?</button>
            `,
            showCancelButton: true,
            confirmButtonText: 'Submit',
            focusConfirm: false,
            customClass: {
                popup: 'p-3 rounded-3',
                title: 'mb-3',
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-secondary'
            },
            preConfirm: () => {
                const oldPin = Swal.getPopup().querySelector('#old-pin-input').value;
                const newPin = Swal.getPopup().querySelector('#new-pin-input').value;

                if (!oldPin || !newPin) {
                    Swal.showValidationMessage('Please enter both the current and new PIN');
                } else if (oldPin.length !== 4 || isNaN(oldPin)) {
                    Swal.showValidationMessage('Current PIN must be a 4-digit number');
                } else if (newPin.length !== 4 || isNaN(newPin)) {
                    Swal.showValidationMessage('New PIN must be a 4-digit number');
                }
                return { oldPin, newPin };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { oldPin, newPin } = result.value;

                $.ajax({
                    url: '/change-pin',
                    method: 'POST',
                    data: {
                        oldPin: oldPin,
                        newPin: newPin
                    },
                    dataType: 'json',
                    success: function (json) {
                        if (json.invalid) {
                            Swal.fire({
                                title: 'Your Current PIN is incorrect.',
                                icon: 'error',
                                confirmButtonText: 'Okay'
                            });
                        }

                        if (json.success) {
                            Swal.fire({
                                title: 'Your PIN has been changed.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log("Error:", textStatus, errorThrown);
                        console.log("Response:", jqXHR.responseText);
                    }
                })
            }
        });

        // Add event listener for the "Forgot PIN" button
        $('#forgot-pin').click(function () {
            Swal.close();
            $('#loadingOverlay').show();

            $.ajax({
                url: '/reset-pin',
                method: 'POST',
                dataType: 'json',
                success: function (json) {
                    if (json.success) {
                        Swal.fire('PIN Reset', 'An email has been sent. Check your inbox for your new PIN...', 'info');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("Error:", textStatus, errorThrown);
                    console.log("Response:", jqXHR.responseText);
                },
                complete: function () {
                    $('#loadingOverlay').hide();
                }
            })
        });
    }

    $('#expenses-table').DataTable({
        'ordering': false
    });

    $('#municipal-table').DataTable({
        order: [
            [1, 'asc']
        ]
    });

    $('#add-expenses').click(function () {
        $('#expenses-modal').modal('show');
    });

    $('#add-expenses-form').on('submit', function (event) {
        event.preventDefault();
        console.log('form submiteed');

        $.ajax({
            url: '/add-expenses',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (json) {
                console.log(json);
                if (json.redirect) {
                    window.location.href = json.redirect;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        });
    });

    $('.edit').click(function () {
        var id = $(this).data('df-id');

        $.ajax({
            url: '/get-df',
            method: 'POST',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (json) {
                $('#municipal').val(json.municipal);
                $('#df').val(json.df);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    });

    $('#edit-df-form').on('submit', function (event) {
        event.preventDefault();

        $.ajax({
            url: '/update-df',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (json) {
                if (json.redirect) {
                    window.location.href = json.redirect;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error:", textStatus, errorThrown);
                console.log("Response:", jqXHR.responseText);
            }
        })
    });

    $('.status').change(function () {
        if ($(this).is(':checked')) {
            var id = $(this).data('df-id');
            var active = 1;
            updateDeliveryFeeStatus(active, id);
        } else {
            var id = $(this).data('df-id');
            var active = 0;
            updateDeliveryFeeStatus(active, id);
        }
    });

    $('#expenses-table').on('click', '.delete', function () {
        var id = $(this).data('expense-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                delExpense(id);
            } else if (result.dismiss === Swal.DismissReason.cancel) {

            }
        });
    });

    $('#change_pin').click(function () {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to change your PIN?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                showChangePinAlert();
            } else if (result.dismiss === Swal.DismissReason.cancel) {

            }
        });
    })

    $('#print_barcodes').click(function () {

        var content = $('#barcodes').html();

        // Append the header to the content
        $('body').html(content);

        // Print the page
        window.print();

        // Restore the original content
        location.reload();
    })
})