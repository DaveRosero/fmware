$(document).ready(function(){
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
        $.ajax({
            url: '/disable-unit',
            method: 'POST',
            data: {
                id : $(this).data('unit-id'),
                status : $(this).data('unit-status')
            },
            dataType: 'json', 
            success: function(feedback){
                if (feedback.redirect) {
                    window.location.href = feedback.redirect;
                }
            }
        });
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