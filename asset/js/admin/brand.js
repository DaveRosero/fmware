$(document).ready(function(){
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
        $.ajax({
            url: '/disable-brand',
            method: 'POST',
            data: {
                id : $(this).data('brand-id'),
                status : $(this).data('brand-status')
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