$(document).ready(function(){
    function updateBrand (id, status) {
        $.ajax({
            url: '/disable-brand',
            method: 'POST',
            data: {
                id : id,
                status : status
            },
            dataType: 'json', 
            success: function(feedback){
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
            }
        });
    }
    $('#brand-table').DataTable({
        order: [
            [1, 'asc']
        ]
    });

    $('#new-brand').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/new-brand',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                } else {
                    $('#brand_feedback').text(feedback.brand_feedback);
                }
            }
        });
    });

    $('.status').on('click', function(){
        var id = $(this).data('brand-id');
        var status = $(this).data('brand-status');
        var checkbox = $(this);

        if ($(this).is(':checked')) {
            Swal.fire({
                title: 'Enabling Brand',
                text: "Do you want to enable this brand?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, enable it!',
                cancelButtonText: 'No, cancel!',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    updateBrand(id, status);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    checkbox.prop('checked', false);
                }
            });
        } else {
            Swal.fire({
                title: 'Disabling Brand',
                text: "Do you want to disable this brand?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, disable it!',
                cancelButtonText: 'No, cancel!',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    updateBrand(id, status);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    checkbox.prop('checked', true);
                }
            });
        }
    });

    $('.edit').on('click', function(){
        $('#edit-label').html('Editing <strong>"' + $(this).data('brand-name') + '</strong>"');
        $('#brand_name').val($(this).data('brand-name'));
        $('#brand_id').val($(this).data('brand-id'));
        $('#editBrand').modal('show');
    });

    $('#edit-brand').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/edit-brand',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                $('#edit_feedback').text(feedback.edit_feedback);
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
            }
        });
    });
});