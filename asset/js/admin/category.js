$(document).ready(function () {
    function updateCategory(id, status) {
        $.ajax({
            url: '/disable-category',
            method: 'POST',
            data: {
                id: id,
                status: status
            },
            dataType: 'json',
            success: function (feedback) {
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
            }
        });
    }
    $('#category-table').DataTable({
        order: [
            [1, 'asc']
        ]
    });

    $('#new-category').on('submit', function (event) {
        event.preventDefault();

        $.ajax({
            url: '/new-category',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (feedback) {
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                } else {
                    $('#category_feedback').text(feedback.category_feedback);
                }
            }
        });
    });

    $('#category-table').on('click', '.status', function () {
        var id = $(this).data('category-id');
        var status = $(this).data('category-status');
        var checkbox = $(this);

        if ($(this).is(':checked')) {
            Swal.fire({
                title: 'Enabling Category',
                text: "Do you want to enable this category?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, enable it!',
                cancelButtonText: 'No, cancel!',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    updateCategory(id, status);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    checkbox.prop('checked', false);
                }
            });
        } else {
            Swal.fire({
                title: 'Disabling Category',
                text: "Do you want to disable this category?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, disable it!',
                cancelButtonText: 'No, cancel!',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    updateCategory(id, status);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    checkbox.prop('checked', true);
                }
            });
        }
    });

    $('#category-table').on('click', '.edit', function () {
        $('#edit-label').html('Editing <strong>"' + $(this).data('category-name') + '</strong>"');
        $('#category_name').val($(this).data('category-name'));
        $('#category_id').val($(this).data('category-id'));
        $('#editCategory').modal('show');
    });

    $('#edit-category').on('submit', function (event) {
        event.preventDefault();

        $.ajax({
            url: '/edit-category',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (feedback) {
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
            }
        });
    });
});