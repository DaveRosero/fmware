$(document).ready(function(){
    function updateUnit (id, status) {
        $.ajax({
            url: '/disable-unit',
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

    $('#unit-table').DataTable({
        order: [
            [1, 'asc']
        ]
    });

    $('#new-unit').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/new-unit',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(feedback){
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                } else {
                    $('#unit_feedback').text(feedback.unit_feedback);
                }
            }
        });
    });

    $('.status').on('click', function(){
        var id = $(this).data('unit-id');
        var status = $(this).data('unit-status');
        var checkbox = $(this);

        if ($(this).is(':checked')) {
            Swal.fire({
                title: 'Enabling Unit',
                text: "Do you want to enable this unit?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, enable it!',
                cancelButtonText: 'No, cancel!',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    updateUnit(id, status);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    checkbox.prop('checked', false);
                }
            });
        } else {
            Swal.fire({
                title: 'Disabling Unit',
                text: "Do you want to disable this unit?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, disable it!',
                cancelButtonText: 'No, cancel!',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    updateUnit(id, status);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    checkbox.prop('checked', true);
                }
            });
        }
    });

    $('.edit').on('click', function(){
        $('#edit-label').html('Editing <strong>"' + $(this).data('unit-name') + '</strong>"');
        $('#unit_name').val($(this).data('unit-name'));
        $('#unit_id').val($(this).data('unit-id'));
        $('#editUnit').modal('show');
    });

    $('#edit-unit').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: '/edit-unit',
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